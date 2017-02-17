<?php

namespace ReddogsTest\Migrations\Command;

use PHPUnit\Framework\TestCase;
use Reddogs\Migrations\Command\GenerateCommand;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Reddogs\Migrations\Helper\ModuleConfigurationHelper;

class GenerateCommandTest extends TestCase
{
    private $generateCommand;

    protected function setUp()
    {
        $this->generateCommand = $this->getMockBuilder(GenerateCommand::class)
            ->setMethods(['generateMigration', 'generateVersionNumber'])
            ->getMock();
    }

    public function testExecute()
    {
        $input = new ArgvInput(['scriptname.php', 'testmodule'], $this->generateCommand->getDefinition());
        $output = $this->getMockBuilder(ConsoleOutput::class)
            ->setMethods(['writeln'])
            ->getMock();
        $helperSet = new HelperSet();

        $connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $connectionHelper = new ConnectionHelper($connection);

        $config = [
            'testmodule' => [
                'namespace' => 'TestNamespace',
                'directory' => __DIR__ . '/_files/migrations',
                'table_name' => 'testtablename'
            ]
        ];

        $moduleConfigurationHelper = new ModuleConfigurationHelper($connection, $config);

        $helperSet->set($connectionHelper, 'connection');
        $helperSet->set($moduleConfigurationHelper, $moduleConfigurationHelper->getName());

        $this->generateCommand->setHelperSet($helperSet);

        $this->generateCommand->expects($this->once())
            ->method('generateMigration')
            ->with($this->isInstanceOf(Configuration::class),
                   $this->identicalTo($input))
            ->will($this->returnValue('testMigrationPath'));

        $output->expects($this->at(0))
            ->method('writeln')
            ->with($this->equalTo('Loading configuration from the integration code of your framework (setter).'));
        $output->expects($this->at(1))
            ->method('writeln')
            ->with($this->equalTo('Generated new migration class to "<info>testMigrationPath</info>"'));
        $output->expects($this->exactly(2))
            ->method('writeln');

        $this->generateCommand->execute($input, $output);
    }
}