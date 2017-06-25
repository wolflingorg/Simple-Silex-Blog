<?php
namespace app;

use Blog\Provider\EventBusServiceProvider;
use Silex\Application;
use Blog\Provider\CommandBusServiceProvider;

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