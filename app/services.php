<?php

namespace app;

use Blog\Provider\CommandBusServiceProvider;
use Blog\Provider\EventBusServiceProvider;
use Silex\Application;

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
}