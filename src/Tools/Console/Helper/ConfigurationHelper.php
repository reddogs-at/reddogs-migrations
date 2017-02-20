<?php
/**
 * Reddogs (https://github.com/reddogs-at)
 *
 * @see https://github.com/reddogs-at/reddogs-migrations for the canonical source repository
 * @license https://github.com/reddogs-at/reddogs-migrations/blob/master/LICENSE MIT License
 */
declare(strict_types = 1);
namespace Reddogs\Migrations\Tools\Console\Helper;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Symfony\Component\Console\Input\InputInterface;
use Doctrine\DBAL\Migrations\OutputWriter;

class ConfigurationHelper extends \Doctrine\DBAL\Migrations\Tools\Console\Helper\ConfigurationHelper
{
    /**
     * Modules config
     *
     * @var array
     */
    private $modulesConfig;

    private $connection;

    /**
     * Constructor
     *
     * @param Connection $connection
     * @param Configuration $configuration
     * @param array $modulesConfig
     */
    public function __construct(Connection $connection = null, Configuration $configuration = null,
        array $modulesConfig = array())
    {
        $this->modulesConfig = $modulesConfig;
        $this->connection = $connection;
        parent::__construct($connection, $configuration);
    }

    /**
     * Get modules config
     *
     * @return array
     */
    public function getModulesConfig() : array
    {
        return $this->modulesConfig;
    }

    public function getConnection() : Connection
    {
        return $this->connection;
    }

    /**
     * Get migration config
     *
     * {@inheritDoc}
     * @see \Doctrine\DBAL\Migrations\Tools\Console\Helper\ConfigurationHelper::getMigrationConfig()
     */
    public function getMigrationConfig(InputInterface $input, OutputWriter $outputWriter) : Configuration
    {
        $modulesConfig = $this->getModulesConfig();
        $moduleName = $input->getArgument('module');
        if (isset($modulesConfig[$moduleName])) {
            $configuration = new Configuration($this->getConnection(), $outputWriter);
            $configuration->setMigrationsNamespace($modulesConfig[$moduleName]['namespace']);
            $configuration->setMigrationsDirectory($modulesConfig[$moduleName]['directory']);
            $configuration->setMigrationsTableName($modulesConfig[$moduleName]['table_name']);
            return $configuration;
        }
        return parent::getMigrationConfig($input, $outputWriter);
    }
}