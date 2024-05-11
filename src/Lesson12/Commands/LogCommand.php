<?php

declare(strict_types=1);

namespace App\Lesson12\Commands;

use App\Lesson01\Command;

readonly class LogCommand implements Command
{
    public function __construct(
        private string $text
    ) {}

    public function execute(): void
    {
        $file = 'src/temp/logs/log.txt';

        $file = fopen($file, 'a');
        fwrite($file, $this->text . PHP_EOL);
        fclose($file);
    }
}
