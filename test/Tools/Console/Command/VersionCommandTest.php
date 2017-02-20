<?php

namespace ReddogsTest\Migrations\Tools\Console\Command;

use PHPUnit\Framework\TestCase;
use Reddogs\Migrations\Tools\Console\Command\VersionCommand;

class VersionCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        $this->command = new VersionCommand();
    }

    public function testConfigure()
    {
        $definition = $this->command->getDefinition();
        $this->assertSame(2, $definition->getArgumentCount());
        $this->assertTrue($definition->hasArgument('module'));
    }
}