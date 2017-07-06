<?php

namespace Blog\Provider;

use Blog\Console\Command\LoadFixturesCommand;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;

class FixtureCommandsServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app->extend('console', function (Application $console, Container $app) {
            $console->setHelperSet(new HelperSet([
                'db' => new ConnectionHelper($app['db'])
            ]));

            $console->add(new LoadFixturesCommand($app['parameters']['app.fixtures_dirs']));

            return $console;
        });
    }
}
