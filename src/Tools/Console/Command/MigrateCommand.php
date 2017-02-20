<?php
/**
 * Reddogs (https://github.com/reddogs-at)
 *
 * @see https://github.com/reddogs-at/reddogs-migrations for the canonical source repository
 * @license https://github.com/reddogs-at/reddogs-migrations/blob/master/LICENSE MIT License
 */
declare(strict_types = 1);
namespace Reddogs\Migrations\Tools\Console\Command;

use Symfony\Component\Console\Input\InputArgument;

class MigrateCommand extends \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand
{
    /**
     * Configure
     *
     * {@inheritDoc}
     * @see \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand::configure()
     */
    protected function configure()
    {
        $this->addArgument('module', InputArgument::REQUIRED, 'The module to generate migration for.', null);
        parent::configure();
    }
}