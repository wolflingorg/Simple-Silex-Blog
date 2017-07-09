<?php

namespace Blog\Provider;

use Doctrine\DBAL\Migrations\Configuration\Configuration;
use Doctrine\DBAL\Migrations\OutputWriter;
use Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\DBAL\Migrations\Tools\Console\Command\UpToDateCommand;
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
            $config = $app['db.migrations'];

            $migrationConfig = new Configuration($app['db'], new OutputWriter(
                function ($message) {
                    $output = new ConsoleOutput();
                    $output->writeln($message);
                }
            ));
            $migrationConfig->setMigrationsDirectory($config['directory']);
            $migrationConfig->setMigrationsNamespace($config['namespace']);
            $migrationConfig->setName($config['name']);
            $migrationConfig->setMigrationsTableName($config['table_name']);
            $migrationConfig->registerMigrationsFromDirectory($config['directory']);

            $commands = array(
                new ExecuteCommand(),
                new GenerateCommand(),
                new MigrateCommand(),
                new StatusCommand(),
                new VersionCommand(),
                new UpToDateCommand()
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
