#!/usr/bin/env php
<?php
require __DIR__ . '/../bootstrap.php';
use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\HelperSet;




$console = new Application;
$helperSet = new HelperSet(array(
    new ConnectionHelper($app['dbal']),
    new DialogHelper()
));


$console->setHelperSet($helperSet);

$console->add(new MigrateCommand());
$console->add(new GenerateCommand());
$console->add(new StatusCommand());
$console->add(new VersionCommand());
$console->add(new ExecuteCommand());
$console->add(new DiffCommand());


$console->run();
