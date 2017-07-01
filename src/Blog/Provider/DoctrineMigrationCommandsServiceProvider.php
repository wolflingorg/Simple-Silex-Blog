<?php

namespace Blog\Provider;

use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\OutputWriter;
use Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\ConsoleOutput;

class DoctrineMigrationCommandsServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app->extend('console', function (Application $console, Container $app) {
            if (!is_dir($app['db.migrations']['directory'])) {
                mkdir($app['db.migrations']['directory'], 0777, true);
            }

            $migrationConfig = new Configuration($app['db'], new OutputWriter(
                function ($message) {
                    $output = new ConsoleOutput();
                    $output->writeln($message);
                }
            ));
            $migrationConfig->setMigrationsDirectory($app['db.migrations']['directory']);
            $migrationConfig->setMigrationsNamespace($app['db.migrations']['namespace']);
            $migrationConfig->setName($app['db.migrations']['name']);
            $migrationConfig->setMigrationsTableName($app['db.migrations']['table_name']);
            $migrationConfig->registerMigrationsFromDirectory($app['db.migrations']['directory']);

            $commands = array(
                new DiffCommand(),
                new ExecuteCommand(),
                new GenerateCommand(),
                new MigrateCommand(),
                new StatusCommand(),
                new VersionCommand(),
            );
            foreach ($commands as $command) {
                /** @var \Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand $command */
                $command->setMigrationConfiguration($migrationConfig);
                $console->add($command);
            }

            return $console;
        });
    }
}