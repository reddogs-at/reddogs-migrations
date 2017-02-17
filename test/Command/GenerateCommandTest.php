<?php

namespace ReddogsTest\Migrations\Command;

use PHPUnit\Framework\TestCase;
use Reddogs\Migrations\Command\GenerateCommand;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Helper\HelperSet;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\DBAL\Migrations\Tools\Console\Helper\ConfigurationHelper;
use Doctrine\DBAL\Migrations\Configuration\Configuration;

class GenerateCommandTest extends TestCase
{
    private $generateCommand;

    protected function setUp()
    {
        $this->generateCommand = new GenerateCommand();
    }

    public function testExecute()
    {
        $input = new ArgvInput(['scriptname.php', 'test'], $this->generateCommand->getDefinition());
        $output = new ConsoleOutput();
        $helperSet = new HelperSet();

        $connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $connectionHelper = new ConnectionHelper($connection);
        $configuration = new Configuration($connection);
        $configuration->setMigrationsDirectory('migrations');
        $configurationHelper = new ConfigurationHelper($connection, $configuration);
        $helperSet->set($connectionHelper, 'connection');
        $helperSet->set($configurationHelper, 'configuration');

        $this->generateCommand->setHelperSet($helperSet);
        $this->generateCommand->execute($input, $output);
    }
}