<?php
/**
 * Reddogs (https://github.com/reddogs-at)
 *
 * @see https://github.com/reddogs-at/reddogs-migrations for the canonical source repository
 * @license https://github.com/reddogs-at/reddogs-migrations/blob/master/LICENSE MIT License
 */
declare(strict_types = 1);
namespace Reddogs\Migrations\Tools;

use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Application;
use Reddogs\Migrations\Command\GenerateCommand;
use Reddogs\Migrations\Command\MigrateCommand;
use Reddogs\Migrations\Command\ExecuteCommand;
use Reddogs\Migrations\Command\LatestCommand;
use Reddogs\Migrations\Command\StatusCommand;
use Reddogs\Migrations\Command\UpToDateCommand;
use Reddogs\Migrations\Command\VersionCommand;

class ConsoleRunner
{

    /**
     * Helper set
     *
     * @var HelperSet
     */
    private $helperSet;

    /**
     * Application
     *
     * @var Application
     */
    private $application;

    /**
     * Constructor
     *
     * @param HelperSet $helperSet
     */
    public function __construct(HelperSet $helperSet)
    {
        $this->helperSet = $helperSet;
    }

    /**
     * Get helper set
     *
     * @return HelperSet
     */
    public function getHelperSet(): HelperSet
    {
        return $this->helperSet;
    }

    /**
     * Create Applicatoin
     *
     * @return Application
     */
    public function createApplication() : Application
    {
        $this->application = new Application('Reddogs Migrations', '1.0.0');
        $this->application->setHelperSet($this->getHelperSet());
        $this->application->add(new ExecuteCommand());
        $this->application->add(new GenerateCommand());
        $this->application->add(new LatestCommand());
        $this->application->add(new MigrateCommand());
        $this->application->add(new StatusCommand());
        $this->application->add(new UpToDateCommand());
        $this->application->add(new VersionCommand());
        return $this->application;
    }
}