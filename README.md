# Reddogs Migrations

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Build Status](https://travis-ci.org/reddogs-at/reddogs-migrations.svg?branch=master)](https://travis-ci.org/reddogs-at/reddogs-migrations)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/reddogs-at/reddogs-migrations/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/reddogs-at/reddogs-migrations/?branch=master) 
[![Coverage Status](https://coveralls.io/repos/github/reddogs-at/reddogs-migrations/badge.svg?branch=master)](https://coveralls.io/github/reddogs-at/reddogs-migrations?branch=master)

Doctrine migrations enhanced with module specific migrations. Allows to to combine migrations of independent modules.

## Installation

composer require reddogs-at/reddogs-migrations

## Configuration

```php
<?php
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Reddogs\Migrations\Tools\Console\Helper\ConfigurationHelper;

$container = require 'config/container.php';
$config = $container->get('config');

return new \Symfony\Component\Console\Helper\HelperSet([
    'connection' => new ConnectionHelper(
        $container->get(EntityManager::class)->getConnection()
    ),
    'configuration' => new ConfigurationHelper(
        $container->get(EntityManager::class)->getConnection(),
        null,
        $config['doctrine']['reddogs_migrations']
    )
]);
```

