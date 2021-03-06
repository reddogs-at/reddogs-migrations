<?php

namespace ReddogsTest\Migrations\Tools;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\HelperSet;
use Reddogs\Migrations\Tools\ConsoleRunner;
use Symfony\Component\Console\Application;
use Reddogs\Migrations\Tools\Console\Command\MigrateCommand;
use Reddogs\Migrations\Tools\Console\Command\ExecuteCommand;
use Reddogs\Migrations\Tools\Console\Command\GenerateCommand;
use Reddogs\Migrations\Tools\Console\Command\LatestCommand;
use Reddogs\Migrations\Tools\Console\Command\StatusCommand;
use Reddogs\Migrations\Tools\Console\Command\UpToDateCommand;
use Reddogs\Migrations\Tools\Console\Command\VersionCommand;
use Reddogs\Migrations\Tools\Console\Command\MigrateAllCommand;

class ConsoleRunnerTest extends TestCase
{
    private $consoleRunner, $helperSet;

    protected function setUp()
    {
        $this->helperSet = new HelperSet();
        $this->consoleRunner = new ConsoleRunner($this->helperSet);
    }

    public function testGetHelperSet()
    {
        $this->assertSame($this->helperSet, $this->consoleRunner->getHelperSet());
    }

    public function testCreateApplication()
    {
        $application = $this->consoleRunner->createApplication();
        $this->assertInstanceOf(Application::class, $application);
        $this->assertSame('Reddogs Migrations', $application->getName());
        $this->assertSame('1.0.0', $application->getVersion());
        $this->assertTrue($application->areExceptionsCaught());
        $this->assertSame($this->helperSet, $application->getHelperSet());

        $this->assertInstanceOf(ExecuteCommand::class, $application->get('migrations:execute'));
        $this->assertInstanceOf(GenerateCommand::class, $application->get('migrations:generate'));
        $this->assertInstanceOf(LatestCommand::class, $application->get('migrations:latest'));
        $this->assertInstanceOf(MigrateCommand::class, $application->get('migrations:migrate'));
        $this->assertInstanceOf(MigrateAllCommand::class, $application->get('migrations:migrate-all'));
        $this->assertInstanceOf(StatusCommand::class, $application->get('migrations:status'));
        $this->assertInstanceOf(UpToDateCommand::class, $application->get('migrations:up-to-date'));
        $this->assertInstanceOf(VersionCommand::class, $application->get('migrations:version'));
    }
}