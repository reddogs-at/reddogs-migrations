<?php

namespace ReddogsTest\Migrations\Tools\Console\Command;

use PHPUnit\Framework\TestCase;
use Reddogs\Migrations\Tools\Console\Command\UpToDateCommand;

class UpToDateCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        $this->command = new UpToDateCommand();
    }

    public function testConfigure()
    {
        $definition = $this->command->getDefinition();
        $this->assertSame(1, $definition->getArgumentCount());
        $this->assertTrue($definition->hasArgument('module'));
    }
}