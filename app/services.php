<?php
use Silex\Application;
use Blog\Provider\CommandBusServiceProvider;
use Blog\Command;
use Blog\Command\Handler;

function services(Application $app)
{
    // command bus
    $app->register(new CommandBusServiceProvider());

    $app['command_handlers'] = function ($app) {
        return [
            Command\CalculateCommand::class => Handler\CalculateCommandHandler::class
        ];
    };
}