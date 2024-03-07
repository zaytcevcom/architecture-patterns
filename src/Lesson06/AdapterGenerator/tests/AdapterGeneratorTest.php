<?php

declare(strict_types=1);

namespace App\Lesson06\AdapterGenerator\tests;

use App\Lesson01\Move\Movable;
use App\Lesson06\AdapterGenerator\AdapterGenerator;
use PHPUnit\Framework\TestCase;
use ReflectionException;

/**
 * @internal
 */
final class AdapterGeneratorTest extends TestCase
{
    /** @throws ReflectionException */
    public function testGenerateAdapter(): void
    {
        $path =  'src/temp/adapters/Lesson01/Move/MovableAdapter.php';

        $adapterGenerator = new AdapterGenerator(
            interface: Movable::class,
            path: $path
        );
        $adapterGenerator->execute();

        $handle = fopen(realpath($path), 'r');
        $generatedCode = fread($handle, filesize($path));
        fclose($handle);

        $expectedCodeStart = '<?php

declare(strict_types=1);

namespace App\temp\adapters\Lesson01\Move;

use App\Lesson01\Move\Movable;
use App\Lesson01\Move\Vector;
use App\Lesson05\IoC\IoC;

readonly class MovableAdapter implements Movable
{
    public function __construct(
        private mixed $obj
    ) {}

    public function getPosition(): Vector
    {
        return IoC::resolve(\'\App\Lesson01\Move\Movable:position.get\', $this->obj);
    }

    public function setPosition(Vector $vector): Vector
    {
        return IoC::resolve(\'\App\Lesson01\Move\Movable:position.set\', $this->obj, $vector);
    }

    public function getVelocity(): Vector
    {
        return IoC::resolve(\'\App\Lesson01\Move\Movable:velocity.get\', $this->obj);
    }
}
';
        self::assertEquals($expectedCodeStart, $generatedCode);
    }
}
