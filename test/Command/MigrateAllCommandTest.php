<?php

namespace Reddogs\Migrations\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Reddogs\Migrations\Helper\ModuleConfigurationHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Migrations\Configuration\Configuration;

class MigrateAllCommandTest extends TestCase
{
    private $command;

    protected function setUp()
    {
        $this->command = $this->getMockBuilder(MigrateAllCommand::class)
            ->setMethods(['configure'])
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetDecoratedCommandName()
    {
        $this->assertSame(
            'Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand',
            $this->command->getDecoratedCommandName()
        );
    }

    public function testConfigureDecorator()
    {
        $definition = new InputDefinition();
        $this->command->setDefinition($definition);
        $this->command->configureDecorator();
        $this->assertSame('migrations:migrate-all', $this->command->getName());
        $this->assertTrue($definition->hasOption('dry-run'));
    }

    public function testExecute()
    {
        $moduleConfigurationHelper = $this->getMockBuilder(ModuleConfigurationHelper::class)
            ->setMethods(['getModuleMigrationConfig', 'getModuleKeys'])
            ->disableOriginalConstructor()
            ->getMock();
        $helperSet = new HelperSet([
            'module-configuration' => $moduleConfigurationHelper
        ]);
        $this->command->setHelperSet($helperSet);

        $decoratedCommand = $this->getMockBuilder(MigrateCommand::class)
            ->setMethods(['execute', 'setMigrationConfiguration'])
            ->getMock();
        $this->command->setDecoratedCommand($decoratedCommand);

        $moduleConfigurationHelper->expects($this->once())
            ->method('getModuleKeys')
            ->will($this->returnValue(['testmodule1', 'testmodule2']));

        $migrationConfig1 = $this->getMockBuilder(Configuration::class)->disableOriginalConstructor()->getMock();
        $migrationConfig2 = $this->getMockBuilder(Configuration::class)->disableOriginalConstructor()->getMock();

        $moduleConfigurationHelper->expects($this->at(1))
            ->method('getModuleMigrationConfig')
            ->with($this->equalTo('testmodule1'))
            ->will($this->returnValue($migrationConfig1));

        $moduleConfigurationHelper->expects($this->at(2))
            ->method('getModuleMigrationConfig')
            ->with($this->equalTo('testmodule2'))
            ->will($this->returnValue($migrationConfig2));

        $decoratedCommand->expects($this->at(0))
            ->method('setMigrationConfiguration')
            ->with($this->equalTo($migrationConfig1));

        $decoratedCommand->expects($this->at(2))
            ->method('setMigrationConfiguration')
            ->with($this->equalTo($migrationConfig2));

        $input = new ArgvInput(['scriptname.php'], $this->command->getDefinition());
        $output = new ConsoleOutput();

        $decoratedCommand->expects($this->exactly(2))
            ->method('execute')
            ->with($this->equalTo($input),
                   $this->equalTo($output));

        $this->command->execute($input, $output);
    }
}