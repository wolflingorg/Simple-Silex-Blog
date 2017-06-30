<?php

namespace Blog\Provider;

use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Application;

class DoctrineMigrationsServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app->extend('console', function (Application $console) {
            $console->add(new DiffCommand());
            $console->add(new ExecuteCommand());
            $console->add(new GenerateCommand());
            $console->add(new MigrateCommand());
            $console->add(new StatusCommand());
            $console->add(new VersionCommand());

            return $console;
        });
    }
}