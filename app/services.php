<?php

namespace app;

use Blog\Provider\CommandBusServiceProvider;
use Blog\Provider\DoctrineCommandsServiceProvider;
use Blog\Provider\DoctrineMigrationCommandsServiceProvider;
use Blog\Provider\EventBusServiceProvider;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;

function services(Application $app)
{
    // command bus
    $app->register(new CommandBusServiceProvider());
    $app['command_handlers'] = function ($app) {
        return [
        ];
    };

    // event bus
    $app->register(new EventBusServiceProvider());
    $app['event_subscribers'] = function ($app) {
        return [
        ];
    };

    $app->register(new DoctrineServiceProvider(), [
        'db.options' => $app['config']['doctrine']['dbal']
    ]);

    $app->register(new DoctrineMigrationCommandsServiceProvider());

    $app->register(new DoctrineCommandsServiceProvider());
}