<?php

namespace ReddogsTest\Migrations\Tools\Console\Command;

use PHPUnit\Framework\TestCase;
use Reddogs\Migrations\Tools\Console\Command\MigrateAllCommand;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArrayInput;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Reddogs\Migrations\Tools\Console\Helper\ConfigurationHelper;

class MigrateAllCommandTest extends TestCase
{
    private $command, $decoratedCommand;

    protected function setUp()
    {
        $this->decoratedCommand = $this->getMockBuilder(MigrateCommand::class)
            ->setMethods([
                'setMigrationConfiguration', 'ignoreValidationErrors', 'mergeApplicationDefinition',
                'run'
            ])
            ->disableOriginalConstructor()
            ->getMock();
        $this->command = new MigrateAllCommand(null, $this->decoratedCommand);
    }

    public function testGetDecoratedCommand()
    {
        $this->assertInstanceOf(MigrateCommand::class, $this->command->getDecoratedCommand());
    }

    public function testSetMigrationConfiguration()
    {
        $config = $this->getMockBuilder(Configuration::class)->disableOriginalConstructor()->getMock();

        $this->decoratedCommand->expects(($this->once()))
            ->method('setMigrationConfiguration')
            ->with($this->equalTo($config));

        $this->command->setMigrationConfiguration($config);
    }

    public function testIgnoreValidationErrors()
    {
        $this->decoratedCommand->expects($this->once())
            ->method('ignoreValidationErrors');

        $this->command->ignoreValidationErrors();
    }

    public function testSetApplication()
    {
        $application = new Application();
        $this->command->setApplication($application);
        $this->assertSame($application, $this->command->getApplication());
        $this->assertSame($application, $this->command->getDecoratedCommand()->getApplication());
    }

    public function testSetHelperSet()
    {
        $helperSet = new HelperSet();
        $this->command->setHelperSet($helperSet);
        $this->assertSame($helperSet, $this->command->getHelperSet());
        $this->assertSame($helperSet, $this->command->getDecoratedCommand()->getHelperSet());
    }

    public function testMergeApplicationDefinition()
    {
        $this->decoratedCommand->expects($this->once())
            ->method('mergeApplicationDefinition')
            ->with($this->equalTo(true));
        $this->command->mergeApplicationDefinition();
    }

    public function testConfigure()
    {
        $this->assertSame('migrations:migrate-all', $this->command->getName());
        $definition = $this->command->getDefinition();
        $this->assertSame(0, $definition->getArgumentCount());
        $this->assertTrue($definition->hasOption('dry-run'));
    }

    public function testExecute()
    {
        $configurationHelper = $this->getMockBuilder(ConfigurationHelper::class)
            ->setMethods(['getMigrationConfig', 'getModuleKeys'])
            ->getMock();

        $helperSet = new HelperSet([
            $configurationHelper->getName() => $configurationHelper,
        ]);
        $this->command->setHelperSet($helperSet);

        $configuration1 = $this->getMockBuilder(Configuration::class)->disableOriginalConstructor()->getMock();
        $configuration2 = $this->getMockBuilder(Configuration::class)->disableOriginalConstructor()->getMock();

        $configurationHelper->expects($this->once())
            ->method('getModuleKeys')
            ->will($this->returnValue(['testmodule1', 'testmodule2']));

        $configurationHelper->expects($this->at(1))
            ->method('getMigrationConfig')
            ->with($this->equalTo(new ArrayInput(['module' => 'testmodule1'])))
            ->will($this->returnValue($configuration1));

        $configurationHelper->expects($this->at(2))
            ->method('getMigrationConfig')
            ->with($this->equalTo(new ArrayInput(['module' => 'testmodule2'])))
            ->will($this->returnValue($configuration2));

        $this->decoratedCommand->expects($this->at(0))
            ->method('setMigrationConfiguration')
            ->with($this->equalTo($configuration1));
        $this->decoratedCommand->expects($this->at(2))
            ->method('setMigrationConfiguration')
            ->with($this->equalTo($configuration2));

        $input = new ArrayInput([], $this->command->getDefinition());
        $output = new ConsoleOutput();

        $this->decoratedCommand->expects($this->at(1))
            ->method('run')
            ->with($this->equalTo($input),
                   $this->equalTo($output));
        $this->decoratedCommand->expects($this->at(3))
            ->method('run')
            ->with($this->equalTo($input),
                $this->equalTo($output));

        $this->decoratedCommand->expects($this->exactly(2))
            ->method('run');

        $this->command->execute($input, $output);
    }
}