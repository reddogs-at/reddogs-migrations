<?php

namespace ReddogsTest\Migrations\Command;

use PHPUnit\Framework\TestCase;
use Reddogs\Migrations\Command\UpToDateCommand;

class UpToDateCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        $this->command = $this->getMockBuilder(UpToDateCommand::class)
            ->setMethods(['configure'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetDecoratedCommandName()
    {
        $this->assertSame(
            'Doctrine\DBAL\Migrations\Tools\Console\Command\UpToDateCommand',
            $this->command->getDecoratedCommandName()
        );
    }
}