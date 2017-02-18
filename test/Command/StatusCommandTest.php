<?php

namespace ReddogsTest\Migrations\Command;

use PHPUnit\Framework\TestCase;
use Reddogs\Migrations\Command\StatusCommand;

class StatusCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        $this->command = $this->getMockBuilder(StatusCommand::class)
            ->setMethods(['configure'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetDecoratedCommandName()
    {
        $this->assertSame(
            'Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand',
            $this->command->getDecoratedCommandName()
        );
    }
}