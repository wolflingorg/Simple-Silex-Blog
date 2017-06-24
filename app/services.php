<?php
use Silex\Application;
use Blog\Provider\CommandBusServiceProvider;

function services(Application $app)
{
    // command bus
    $app->register(new CommandBusServiceProvider());

    $app['command_handlers'] = function ($app) {
        return [
            'Blog\\Command\\CalculateCommand' => 'Blog\\Command\\Handler\\CalculateCommandHandler'
        ];
    };
}