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
    protected IoC $ioc;
    protected CurrentScopeCommand $csc;

    protected function setUp(): void
    {
        $this->ioc = new IoC();
        $this->csc = new CurrentScopeCommand($this->ioc, 'newScope');

        parent::setUp();
    }

    public function testExecute(): void
    {
        self::assertNotEquals('newScope', $this->ioc->getCurrentScope());

        $this->csc->execute();

        self::assertEquals('newScope', $this->ioc->getCurrentScope());
    }
}
