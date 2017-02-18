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
use Symfony\Component\Console\Command\Command;

class GenerateCommand extends AbstractCommand
{
    /**
     * Get decorated command name
     * {@inheritDoc}
     * @see \Reddogs\Migrations\Command\AbstractCommand::getDecoratedCommandName()
     */
    public function getDecoratedCommandName() : string
    {
        return DoctrineGenerateCommand::class;
    }
}