<?php

declare(strict_types=1);

namespace App\Lesson06\AdapterGenerator;

use App\Lesson01\Command;
use App\Lesson05\IoC\IoC;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;

class AdapterGenerator implements Command
{
    /** @var string[] */
    private array $imports = [
        IoC::class,
    ];

    public function __construct(
        /** @var class-string */
        private readonly string $interface,
        private readonly string $path = ''
    ) {}

    /** @throws ReflectionException */
    public function execute(): void
    {
        if (file_exists($this->path)) {
            return;
        }

        $code = $this->generateCode();
        $this->saveToFile($code, $this->path);
    }

    /** @throws ReflectionException */
    private function getClassName(): string
    {
        $reflection = new ReflectionClass($this->interface);

        return $reflection->getShortName() . 'Adapter';
    }

    /** @throws ReflectionException */
    private function generateCode(): string
    {
        $reflection = new ReflectionClass($this->interface);

        $this->addImport($this->interface);
        $interfaceName = explode('\\', $this->interface);
        $interfaceName = end($interfaceName);

        $namespace = $this->pathToNamespace();
        $className = $this->getClassName();
        $methods = $this->generateMethods($reflection->getMethods());
        $imports = $this->generateImports();

        return
            '<?php' . PHP_EOL .
            PHP_EOL .
            'declare(strict_types=1);' . PHP_EOL .
            PHP_EOL .
            'namespace ' . $namespace . ';' . PHP_EOL .
            PHP_EOL .
            $imports .
            PHP_EOL .
            'readonly class ' . $className . ' implements ' . $interfaceName . PHP_EOL .
            '{' . PHP_EOL .
            '    public function __construct(' . PHP_EOL .
            '        private mixed $obj' . PHP_EOL .
            '    ) {}' . PHP_EOL .
            PHP_EOL .
            $methods .
            '}' . PHP_EOL;
    }

    /** @param ReflectionMethod[] $methods */
    private function generateMethods(array $methods): string
    {
        $text = '';

        $i = 0;

        foreach ($methods as $method) {
            ++$i;

            $methodParams = $method->getParameters();
            $params = array_map(
                function ($param) {
                    /** @var ReflectionNamedType|null $type */
                    $type = $param->getType();
                    $type = $type?->getName() ?? '';
                    if ($type !== '') {
                        $this->addImport($type);
                        $arr = explode('\\', $type);
                        $type = end($arr);
                    }

                    return trim($type . ' $' . $param->getName());
                },
                $methodParams
            );
            $params = implode(', ', $params);

            $paramsIoC = array_map(
                static function ($param) {
                    return '$' . $param->getName();
                },
                $methodParams
            );
            $paramsIoC = (\count($paramsIoC) > 0) ? ', ' . implode(', ', $paramsIoC) : '';

            /** @var ReflectionNamedType|null $returnType */
            $returnType = $method->getReturnType();
            $returnType = $returnType?->getName() ?? '';

            if ($returnType !== '') {
                $this->addImport($returnType);
                $arr = explode('\\', $returnType);
                $returnType = ': ' . end($arr);
            }

            $methodName = $this->formatMethodName($method->getName());

            $text .=
                '    public function ' . $method->getName() . '(' . $params . ')' . $returnType . PHP_EOL .
                '    {' . PHP_EOL .
                '        return IoC::resolve(\'\\' . $this->interface . ':' . $methodName . '\', $this->obj' . $paramsIoC . ');' . PHP_EOL .
                '    }' . PHP_EOL;

            if ($i < \count($methods)) {
                $text .= PHP_EOL;
            }
        }

        return $text;
    }

    private function generateImports(): string
    {
        sort($this->imports);

        $text = '';
        foreach ($this->imports as $import) {
            $text .= 'use ' . $import . ';' . PHP_EOL;
        }

        return $text;
    }

    private function saveToFile(string $code, string $filePath): void
    {
        $dirPath = \dirname($filePath);

        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0777, true);
        }

        file_put_contents($filePath, $code);
    }

    private function addImport(string $src): void
    {
        if (\in_array($src, $this->imports, true)) {
            return;
        }

        $this->imports[] = $src;
    }

    private function formatMethodName(string $methodName): string
    {
        if (preg_match('/^(get|set)([A-Z])(.*)$/', $methodName, $matches)) {
            $newName = strtolower($matches[2] . $matches[3]) . '.' . $matches[1];
        } else {
            $newName = $methodName;
        }

        return $newName;
    }

    private function pathToNamespace(): string
    {
        $lastSlashPosition = strrpos($this->path, '/');
        if (!$lastSlashPosition) {
            $lastSlashPosition = 0;
        }

        $dir = '/' . substr($this->path, 0, $lastSlashPosition);

        $path = preg_replace('~^.*/src/~', 'App/', $dir);

        return str_replace('/', '\\', $path);
    }
}
