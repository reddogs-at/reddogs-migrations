<?php
/**
 * Reddogs (https://github.com/reddogs-at)
*
* @see https://github.com/reddogs-at/reddogs-migrations for the canonical source repository
* @license https://github.com/reddogs-at/reddogs-migrations/blob/master/LICENSE MIT License
*/
declare(strict_types = 1);
namespace Reddogs\Migrations\Command;

use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand as DoctrineMigrateCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MigrateAllCommand extends AbstractCommand
{
    /**
     * Get decorated command name
     * {@inheritDoc}
     * @see \Reddogs\Migrations\Command\AbstractCommand::getDecoratedCommandName()
     */
    public function getDecoratedCommandName() : string
    {
        return DoctrineMigrateCommand::class;
    }

    /**
     * Configure decorator
     */
    public function configureDecorator()
    {
        $this->setName('migrations:migrate-all');
        $this->setDescription('Execute migrations for all modules to the latest available version');

        $decoratedCommand = $this->getDecoratedCommand();
        $decoratedCommandDefinition = $decoratedCommand->getDefinition();
        $definition = $this->getDefinition();
        $definition->addOption($decoratedCommandDefinition->getOption('dry-run'));
    }

    /**
     * Execute command
     *
     * {@inheritDoc}
     * @see \Reddogs\Migrations\Command\AbstractCommand::execute()
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $moduleConfigurationHelper = $this->getHelper('module-configuration');
        foreach ($moduleConfigurationHelper->getModuleKeys() as $moduleKey) {
            $decoratedCommand = $this->getDecoratedCommand();
            $decoratedCommand->setMigrationConfiguration(
                $moduleConfigurationHelper->getModuleMigrationConfig($moduleKey)
            );
            $decoratedCommand->mergeApplicationDefinition();
            $decoratedCommand->run($input, $output);
        }
    }
}