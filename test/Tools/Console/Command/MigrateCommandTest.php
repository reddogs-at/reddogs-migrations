<?php

namespace ReddogsTest\Migrations\Tools\Console\Command;

use PHPUnit\Framework\TestCase;
use Reddogs\Migrations\Tools\Console\Command\MigrateCommand;

class MigrateCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        $this->command = new MigrateCommand();
    }

    public function testConfigure()
    {
        $definition = $this->command->getDefinition();
        $this->assertSame(2, $definition->getArgumentCount());
        $this->assertTrue($definition->hasArgument('module'));
    }
}