<?php
namespace app;

use Blog\Provider\EventBusServiceProvider;
use Silex\Application;
use Blog\Provider\CommandBusServiceProvider;
use Blog\Command;
use Blog\Event;

function services(Application $app)
{
    // command bus
    $app->register(new CommandBusServiceProvider());

    $app['command_handlers'] = function ($app) {
        return [
            Command\CalculateCommand::class => [Command\Handler\CalculateCommandHandler::class, [$app['event_bus']]]
        ];
    };

    // event bus
    $app->register(new EventBusServiceProvider());

    $app['event_subscribers'] = function ($app) {
        return [
            Event\CalculationFinishedEvent::class => [
                Event\Handler\CalculationFinishedEventHandler::class
            ]
        ];
    };
}