<?php
/**
 * Reddogs (https://github.com/reddogs-at)
 *
 * @see https://github.com/reddogs-at/reddogs-migrations for the canonical source repository
 * @license https://github.com/reddogs-at/reddogs-migrations/blob/master/LICENSE MIT License
 */
declare(strict_types = 1);
namespace Reddogs\Migrations\Helper;

use Symfony\Component\Console\Helper\Helper;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Migrations\Configuration\Configuration;

class ModuleConfigurationHelper extends Helper
{
    /**
     * Connection
     *
     * @var Connection
     */
    private $connection;

    /**
     * Config
     *
     * @var array
     */
    private $config;

    public function __construct(Connection $connection, array $config)
    {
        $this->connection = $connection;
        $this->config = $config;
    }

    /**
     * Get name
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Console\Helper\HelperInterface::getName()
     */
    public function getName() : string
    {
        return 'module-configuration';
    }

    /**
     * Get connection
     *
     * @return Connection
     */
    public function getConnection() : Connection
    {
        return $this->connection;
    }

    /**
     * Get config
     *
     * @return array
     */
    public function getConfig() : array
    {
        return $this->config;
    }

    /**
     * Get module migration config
     *
     * @param string $moduleName
     * @return Configuration
     */
    public function getModuleMigrationConfig(string $moduleName) : Configuration
    {
        $config = $this->getConfig();
        $moduleConfig = $config[$moduleName];
        $migrationConfig = new Configuration($this->getConnection());
        $migrationConfig->setMigrationsNamespace($moduleConfig['namespace']);
        $migrationConfig->setMigrationsDirectory($moduleConfig['directory']);
        $migrationConfig->setMigrationsTableName($moduleConfig['table_name']);
        return $migrationConfig;
    }

    /**
     * Get module keys
     *
     * @return array
     */
    public function getModuleKeys() : array
    {
        return array_keys($this->getConfig());
    }
}