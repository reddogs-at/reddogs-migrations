<?php
/**
 * Reddogs (https://github.com/reddogs-at)
 *
 * @see https://github.com/reddogs-at/reddogs-migrations for the canonical source repository
 * @license https://github.com/reddogs-at/reddogs-migrations/blob/master/LICENSE MIT License
 */
declare(strict_types = 1);
namespace Reddogs\Migrations\Tools\Console\Command;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand as DoctrineMigrateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand;
use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\ArrayInput;
use Doctrine\DBAL\Migrations\OutputWriter;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputArgument;

class MigrateAllCommand extends AbstractCommand
{
    /**
     * Decorated command
     *
     * @var DoctrineMigrateCommand
     */
    private $decoratedCommand;

    /**
     * Constructor
     * @param string $name
     * @param DoctrineMigrateCommand $decoratedCommand
     */
    public function __construct($name = null, DoctrineMigrateCommand $decoratedCommand)
    {
        $this->decoratedCommand = $decoratedCommand;
        parent::__construct($name);
    }

    /**
     * Get decorated command
     *
     * @return DoctrineMigrateCommand
     */
    public function getDecoratedCommand() : DoctrineMigrateCommand
    {
        return $this->decoratedCommand;
    }

    /**
     * Set migration config
     * {@inheritDoc}
     * @see \Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand::setMigrationConfiguration()
     */
    public function setMigrationConfiguration(Configuration $config)
    {
        $this->getDecoratedCommand()->setMigrationConfiguration($config);
        parent::setMigrationConfiguration($config);
    }

    public function ignoreValidationErrors()
    {
        $this->getDecoratedCommand()->ignoreValidationErrors();
        parent::ignoreValidationErrors();
    }

    /**
     * Set applicatoin
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Console\Command\Command::setApplication()
     */
    public function setApplication(Application $application = null)
    {
        $this->getDecoratedCommand()->setApplication($application);
        parent::setApplication($application);
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

    public function mergeApplicationDefinition($mergeArgs = true)
    {
        $this->getDecoratedCommand()->mergeApplicationDefinition($mergeArgs);
        parent::mergeApplicationDefinition($mergeArgs);
    }

    /**
     * Configure
     *
     * {@inheritDoc}
     * @see \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand::configure()
     */
    protected function configure()
    {
        $this->setName('migrations:migrate-all')
             ->setDescription('Execute migrations for all modules to the latest available version.')
             ->addOption('dry-run', null, InputOption::VALUE_NONE, 'Execute the migration as a dry run.');
    }

    /**
     * Execute command
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Console\Command\Command::run()
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $configurationHelper = $this->getHelper('configuration');
        $decoratedCommand = $this->getDecoratedCommand();
        foreach ($configurationHelper->getModuleKeys() as $moduleKey)
        {
            $inputDefinition = new InputDefinition();
            $inputDefinition->addArgument(new InputArgument('module'));
            $argumentInput = new ArrayInput(['module' => $moduleKey], $inputDefinition);
            $outputWriter = new OutputWriter(function($message) use ($output) {
                return $output->writeln($message);
            });
            $migrationConfig = $configurationHelper->getMigrationConfig($argumentInput, $outputWriter);
            $decoratedCommand->setMigrationConfiguration($migrationConfig);
            $helperSet = new HelperSet([
                'connection' => $decoratedCommand->getHelper('connection'),
                'question' => $decoratedCommand->getHelper('question')
            ]);
            $decoratedCommand->setHelperSet($helperSet);
            $decoratedCommand->run($input, $output);
        }
    }
}