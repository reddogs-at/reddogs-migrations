<?php

namespace ReddogsTest\Migrations\Tools;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Helper\HelperSet;
use Reddogs\Migrations\Tools\ConsoleRunner;
use Symfony\Component\Console\Application;

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

    public function testCreateApplicatoin()
    {
        $application = $this->consoleRunner->createApplication();
        $this->assertInstanceOf(Application::class, $application);
        $this->assertSame('Reddogs Migrations', $application->getName());
        $this->assertSame('1.0.0', $application->getVersion());
        $this->assertTrue($application->areExceptionsCaught());
        $this->assertSame($this->helperSet, $application->getHelperSet());
    }
}