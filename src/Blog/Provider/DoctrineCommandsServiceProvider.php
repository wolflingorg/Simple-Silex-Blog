<?php

namespace Blog\Provider;

use Doctrine\DBAL\Tools\Console\Command\ImportCommand;
use Doctrine\DBAL\Tools\Console\Command\ReservedWordsCommand;
use Doctrine\DBAL\Tools\Console\Command\RunSqlCommand;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;

class DoctrineCommandsServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritdoc
     */
    public function register(Container $app)
    {
        $app->extend('console', function (Application $console, Container $app) {
            $console->setHelperSet(new HelperSet([
                'db' => new ConnectionHelper($app['dbs']['console'])
            ]));

            $commands = array(
                new ImportCommand(),
                new ReservedWordsCommand(),
                new RunSqlCommand()
            );
            foreach ($commands as $command) {
                $console->add($command);
            }

            return $console;
        });
    }
}
