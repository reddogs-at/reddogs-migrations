<?php

namespace ReddogsTest\Migrations\Command;

use PHPUnit\Framework\TestCase;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand as DecoratedAbstractCommand;
use Reddogs\Migrations\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Input\ArgvInput;
use Reddogs\Migrations\Helper\ModuleConfigurationHelper;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;

class AbstractCommandTest extends TestCase
{
    private $command, $decoratedCommand;

    protected function setUp()
    {
        $this->decoratedCommand = $this->getMockBuilder(DecoratedAbstractCommand::class)
            ->setMethods(['execute', 'setMigrationConfiguration'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $this->command = $this->getMockBuilder(AbstractCommand::class)
            ->setMethods(['configure'])
            ->setConstructorArgs(['testname'])
            ->getMockForAbstractClass();
        $this->command->setDecoratedCommand($this->decoratedCommand);
    }

    public function testConfigureDecorator()
    {
        $definition = new InputDefinition();
        $inputArgument = new InputArgument('testArgument');
        $definition->addArgument($inputArgument);
        $inputOption = new InputOption('testOption');
        $definition->addOption($inputOption);

        $this->decoratedCommand->setDescription('testDescription');
        $this->decoratedCommand->setDefinition($definition);

        $this->command->configureDecorator();
        $this->assertSame('testDescription', $this->command->getDescription());


        $decoratorDefinition = $this->command->getDefinition();
        $this->assertSame(2, $decoratorDefinition->getArgumentCount());
        $this->assertSame(['module', 'testArgument'], array_keys($decoratorDefinition->getArguments()));
        $this->assertCount(1, $decoratorDefinition->getOptions());
    }

    public function testGetDecoratedCommand()
    {
        $this->assertSame($this->decoratedCommand, $this->command->getDecoratedCommand());
    }

    public function testGetDecoratedCommandByCommandName()
    {
        $command = $this->getMockBuilder(AbstractCommand::class)
            ->setMethods(['configure', 'getDecoratedCommandName'])
            ->setConstructorArgs(['testname'])
            ->getMockForAbstractClass();
        $command->expects($this->once())
            ->method('getDecoratedCommandName')
            ->will($this->returnValue(StatusCommand::class));
        $this->assertInstanceOf(StatusCommand::class, $command->getDecoratedCommand());
    }

    public function testSetDecoratedCommand()
    {
        $decoratedCommand = new ExecuteCommand();
        $this->command->setDecoratedCommand($decoratedCommand);
        $this->assertSame($decoratedCommand, $this->command->getDecoratedCommand());
    }

    public function testSetHelperSet()
    {
        $helperSet = new HelperSet();
        $this->command->setHelperSet($helperSet);
        $this->assertSame($helperSet, $this->command->getHelperSet());
        $this->assertSame($helperSet, $this->command->getDecoratedCommand()->getHelperSet());
    }

    public function testExecute()
    {
        $moduleConfigurationHelper = $this->getMockBuilder(ModuleConfigurationHelper::class)
            ->setMethods(['getModuleMigrationConfig'])
            ->disableOriginalConstructor()
            ->getMock();
        $helperSet = new HelperSet([
            'module-configuration' => $moduleConfigurationHelper
        ]);
        $this->command->setHelperSet($helperSet);

        $migrationConfiguration = $this->getMockBuilder(Configuration::class)
            ->disableOriginalConstructor()
            ->getMock();

        $moduleConfigurationHelper->expects($this->once())
            ->method('getModuleMigrationConfig')
            ->with($this->equalTo('testmodule'))
            ->will($this->returnValue($migrationConfiguration));

        $this->command->addArgument('module', InputArgument::REQUIRED, 'The module to generate migration for.', null);
        $input = new ArgvInput(['scriptname.php', 'testmodule'], $this->command->getDefinition());
        $output = new ConsoleOutput();

        $this->decoratedCommand->expects($this->once())
            ->method('setMigrationConfiguration')
            ->with($this->equalTo($migrationConfiguration));

        $this->decoratedCommand->expects($this->once())
            ->method('execute')
            ->with($this->equalTo($input),
                   $this->equalTo($output));

        $this->command->execute($input, $output);
    }
}