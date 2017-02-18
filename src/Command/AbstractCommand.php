<?php
/**
 * Reddogs (https://github.com/reddogs-at)
 *
 * @see https://github.com/reddogs-at/reddogs-migrations for the canonical source repository
 * @license https://github.com/reddogs-at/reddogs-migrations/blob/master/LICENSE MIT License
 */
declare(strict_types = 1);
namespace Reddogs\Migrations\Command;

use Symfony\Component\Console\Command\Command;
use Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand as DecoratedCommand;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    /**
     * Decorated command
     *
     * @var AbstractCommand
     */
    private $decoratedCommand;

    /**
     * Configure
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $this->configureDecorator();
        parent::configure();
    }

    /**
     * Get decorated command
     *
     * @return DecoratedCommand
     */
    public function getDecoratedCommand() : DecoratedCommand
    {
        if (null === $this->decoratedCommand) {
            $commandName = $this->getDecoratedCommandName();
            $this->decoratedCommand = new $commandName;
        }
        return $this->decoratedCommand;
    }

    /**
     * Get decorated command name
     *
     * @return string
     */
    abstract public function getDecoratedCommandName() : string;

    /**
     * Set decorated command
     *
     * @param DecoratedCommand $decoratedCommand
     */
    public function setDecoratedCommand(DecoratedCommand $decoratedCommand)
    {
        $this->decoratedCommand = $decoratedCommand;
    }

    /**
     * Set helper set
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Console\Command\Command::setHelperSet()
     */
    public function setHelperSet(HelperSet $helperSet)
    {
        $this->getDecoratedCommand()->setHelperSet($helperSet);
        parent::setHelperSet($helperSet);
    }

    /**
     * Configure decorator
     */
    public function configureDecorator()
    {
        $decoratedCommand = $this->getDecoratedCommand();
        $definition = $decoratedCommand->getDefinition();
        $this->setName($decoratedCommand->getName());
        $this->setDescription($decoratedCommand->getDescription());
        $this->addArgument('module', InputArgument::REQUIRED, 'The module to generate migration for.', null);
        foreach ($definition->getArguments() as $argument)
        {
            $this->getDefinition()->addArgument($argument);
        }
        foreach ($definition->getOptions() as $option) {
            $this->getDefinition()->addOption($option);
        }
    }

    /**
     * Execute
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Console\Command\Command::execute()
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $decoratedCommand = $this->getDecoratedCommand();
        $decoratedCommand->setMigrationConfiguration(
            $this->getHelper('module-configuration')->getModuleMigrationConfig($input->getArgument('module'))
        );
        $decoratedCommand->execute($input, $output);
    }
}