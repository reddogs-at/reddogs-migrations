<?php
/**
 * Reddogs (https://github.com/reddogs-at)
 *
 * @see https://github.com/reddogs-at/reddogs-migrations for the canonical source repository
 * @license https://github.com/reddogs-at/reddogs-migrations/blob/master/LICENSE MIT License
 */
declare(strict_types = 1);
namespace Reddogs\Migrations\Command;

use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand as DoctrineGenerateCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends DoctrineGenerateCommand
{
    protected function configure()
    {
        $this->addArgument('module', InputArgument::REQUIRED, 'The module to generate migration for.', null);
        parent::configure();
    }

    /**
     * Execute generate command
     *
     * {@inheritDoc}
     * @see \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand::execute()
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $module = $input->getArgument('module');
        $moduleConfigHelper = $this->getHelperSet()->get('module-configuration');
        $configuration = $moduleConfigHelper->getModuleMigrationConfig($module);
        $this->setMigrationConfiguration($configuration);
        parent::execute($input, $output);
    }
}