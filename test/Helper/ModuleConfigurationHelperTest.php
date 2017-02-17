<?php

namespace ReddogsTest\Migrations\Helper;

use PHPUnit\Framework\TestCase;
use Reddogs\Migrations\Helper\ModuleConfigurationHelper;
use Doctrine\DBAL\Connection;

class ModuleConfigurationHelperTest extends TestCase
{
    private $helper, $config, $connection;

    protected function setUp()
    {
        $this->config = [
            'testmodulename' => [
                'namespace' => 'TestNamespace',
                'directory' => 'testdirectory',
                'table_name' => 'testtablename'
            ]
        ];
        $this->connection = $this->getMockBuilder(Connection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->helper = new ModuleConfigurationHelper($this->connection, $this->config);
    }

    public function testGetName()
    {
        $this->assertSame('module-configuration', $this->helper->getName());
    }

    public function testGetConnection()
    {
        $this->assertSame($this->connection, $this->helper->getConnection());
    }

    public function testGetConfig()
    {
        $this->assertSame($this->config, $this->helper->getConfig());
    }

    public function testGetModuleMigrationConfig()
    {
        $moduleMigrationConfig = $this->helper->getModuleMigrationConfig('testmodulename');
        $this->assertSame('TestNamespace', $moduleMigrationConfig->getMigrationsNamespace());
        $this->assertSame('testdirectory', $moduleMigrationConfig->getMigrationsDirectory());
        $this->assertSame('testtablename', $moduleMigrationConfig->getMigrationsTableName());
    }

}