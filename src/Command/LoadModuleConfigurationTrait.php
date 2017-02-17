<?php
/**
 * Reddogs (https://github.com/reddogs-at)
 *
 * @see https://github.com/reddogs-at/reddogs-migrations for the canonical source repository
 * @license https://github.com/reddogs-at/reddogs-migrations/blob/master/LICENSE MIT License
 */
declare(strict_types = 1);
namespace Reddogs\Migrations\Command;

trait LoadModuleConfigurationTrait
{
    /**
     * Load module configuration
     *
     * @param string $module
     */
    public function loadModuleConfiguration(string $module)
    {
        $this->setMigrationConfiguration(
            $this->getHelperSet()->get('module-configuration')->getModuleMigrationConfig($module)
        );
    }
}