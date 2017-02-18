<?php

namespace ReddogsTest\Migrations\Command;

use PHPUnit\Framework\TestCase;
use Reddogs\Migrations\Command\LatestCommand;

class LatestCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        $this->command = $this->getMockBuilder(LatestCommand::class)
            ->setMethods(['configure'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetDecoratedCommandName()
    {
        $this->assertSame(
            'Doctrine\DBAL\Migrations\Tools\Console\Command\LatestCommand',
            $this->command->getDecoratedCommandName()
        );
    }
}