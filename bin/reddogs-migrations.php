<?php

use Reddogs\Migrations\Tools\ConsoleRunner;

$autoloadFiles = [
    __DIR__ . '/../vendor/autoload.php',
    __DIR__ . '/../../../autoload.php'
];

$autoloader = false;
foreach ($autoloadFiles as $autoloadFile) {
    if (file_exists($autoloadFile)) {
        require_once $autoloadFile;
        $autoloader = true;
    }
}

if (!$autoloader) {
    if (extension_loaded('phar') && ($uri = Phar::running())) {
        echo 'The phar has been built without dependencies' . PHP_EOL;
    }
    die('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}

$directories = [getcwd(), getcwd() . DIRECTORY_SEPARATOR . 'config'];

$configFile = null;
foreach ($directories as $directory) {
    $configFile = $directory . DIRECTORY_SEPARATOR . 'cli-config.php';

    if (file_exists($configFile)) {
        break;
    }
}

if (file_exists($configFile)) {
    if ( ! is_readable($configFile)) {
        trigger_error(
            'Configuration file [' . $configFile . '] does not have read permission.', E_USER_ERROR
            );
    }

    $helperSet = require $configFile;

    if ( ! ($helperSet instanceof \Symfony\Component\Console\Helper\HelperSet)) {
        foreach ($GLOBALS as $helperSetCandidate) {
            if ($helperSetCandidate instanceof \Symfony\Component\Console\Helper\HelperSet) {
                $helperSet = $helperSetCandidate;
                break;
            }
        }
    }
}

$helperSet = ($helperSet) ?: new \Symfony\Component\Console\Helper\HelperSet();

$input = null;
$output = null;
$consoleRunner = new ConsoleRunner($helperSet);
$application = $consoleRunner->createApplication();
$application->run($input, $output);
