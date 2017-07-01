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
            $appConfig = $app['config']['doctrine']['migrations'];

            if (!is_dir($appConfig['directory'])) {
                mkdir($appConfig['directory'], 0777, true);
            }

            $migrationConfig = new Configuration($app['db'], new OutputWriter(
                function ($message) {
                    $output = new ConsoleOutput();
                    $output->writeln($message);
                }
            ));
            $migrationConfig->setMigrationsDirectory($appConfig['directory']);
            $migrationConfig->setMigrationsNamespace($appConfig['namespace']);
            $migrationConfig->setName($appConfig['name']);
            $migrationConfig->setMigrationsTableName($appConfig['table_name']);
            $migrationConfig->registerMigrationsFromDirectory($appConfig['directory']);

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