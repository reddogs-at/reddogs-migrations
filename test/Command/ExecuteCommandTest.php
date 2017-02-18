<?php

namespace ReddogsTest\Migrations\Command;

use PHPUnit\Framework\TestCase;
use Reddogs\Migrations\Command\ExecuteCommand;

class ExecuteCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        $this->command = $this->getMockBuilder(ExecuteCommand::class)
            ->setMethods(['configure'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetDecoratedCommandName()
    {
        $this->assertSame(
            'Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand',
            $this->command->getDecoratedCommandName()
        );
    }
}