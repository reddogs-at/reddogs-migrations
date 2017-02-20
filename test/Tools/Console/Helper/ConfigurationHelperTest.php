<?php

namespace ReddogsTest\Migrations\Tools\Console\Helper;

use PHPUnit\Framework\TestCase;
use Reddogs\Migrations\Tools\Console\Helper\ConfigurationHelper;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;
use Doctrine\DBAL\Migrations\OutputWriter;
use Doctrine\DBAL\Connection;

class ConfigurationHelperTest extends TestCase
{
    private $helper, $modulesConfig, $connection;

    protected function setUp()
    {
        $this->modulesConfig = [
            'testModule' => [
                'namespace' => 'TestNamespace',
                'directory' => 'testdirectory',
                'table_name' => 'testtablename'
            ]
        ];
        $this->connection = $this->getMockBuilder(Connection::class)->disableOriginalConstructor()->getMock();
        $this->helper = new ConfigurationHelper($this->connection, null, $this->modulesConfig);
    }

    public function testGetModulesConfig()
    {
        $this->assertSame($this->modulesConfig, $this->helper->getModulesConfig());
    }

    public function testGetConnection()
    {
        $this->assertSame($this->connection, $this->helper->getConnection());
    }

    public function testGetMigrationConfig()
    {
        $definition = new InputDefinition();
        $definition->addArgument(new InputArgument('module'));
        $input = new ArrayInput(['module' => 'testModule'], $definition);
        $outputWriter = new OutputWriter();

        $migrationConfig = $this->helper->getMigrationConfig($input, $outputWriter);
        $this->assertSame('TestNamespace', $migrationConfig->getMigrationsNamespace());
        $this->assertSame('testdirectory', $migrationConfig->getMigrationsDirectory());
        $this->assertSame('testtablename', $migrationConfig->getMigrationsTableName());
    }

    public function testGetModuleKeys()
    {
        $this->assertSame(['testModule'], $this->helper->getModuleKeys());
    }
}