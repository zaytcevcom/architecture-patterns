<?php

declare(strict_types=1);

namespace App\Lesson05\CurrentScope\tests;

use App\Lesson05\CurrentScope\CurrentScopeCommand;
use App\Lesson05\IoC\IoC;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
final class CurrentScopeCommandTest extends TestCase
{
    protected CurrentScopeCommand $csc;

    protected function setUp(): void
    {
        $this->csc = new CurrentScopeCommand('newScope');

        parent::setUp();
    }

    public function testExecute(): void
    {
        self::assertNotEquals('newScope', IoC::getCurrentScope());

        $this->csc->execute();

        self::assertEquals('newScope', IoC::getCurrentScope());
    }
}
