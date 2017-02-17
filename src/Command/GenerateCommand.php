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

class GenerateCommand extends DoctrineGenerateCommand
{
    protected function configure()
    {
        $this->addArgument('module', InputArgument::REQUIRED, 'The module to generate migration for.', null);
        parent::configure();
    }
}