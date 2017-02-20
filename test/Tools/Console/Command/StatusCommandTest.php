<?php

namespace ReddogsTest\Migrations\Tools\Console\Command;

use PHPUnit\Framework\TestCase;
use Reddogs\Migrations\Tools\Console\Command\StatusCommand;

class StatusCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        $this->command = new StatusCommand();
    }

    public function testConfigure()
    {
        $definition = $this->command->getDefinition();
        $this->assertSame(1, $definition->getArgumentCount());
        $this->assertTrue($definition->hasArgument('module'));
    }
}