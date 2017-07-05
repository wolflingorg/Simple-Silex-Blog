<?php

namespace app;

use Blog\CommandBus\Command\CreatePostCommand;
use Blog\CommandBus\Handler\CreatePostCommandHandler;
use Blog\Provider\CommandBusMiddlewareServiceProvider;
use Blog\Provider\CommandBusServiceProvider;
use Blog\Provider\DoctrineCommandsServiceProvider;
use Blog\Provider\DoctrineMigrationCommandsServiceProvider;
use Blog\Provider\EventBusServiceProvider;
use Blog\Provider\OutputBuilderServiceProvider;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

function services(Application $app)
{
    // command bus
    $app->register(new CommandBusServiceProvider());
    $app->register(new CommandBusMiddlewareServiceProvider());
    $app['command_handlers'] = function () {
        return [
            CreatePostCommand::class => 'command_bus_create_post_command_handler'
        ];
    };
    $app['command_bus_create_post_command_handler'] = function () {
        return new CreatePostCommandHandler();
    };

    // event bus
    $app->register(new EventBusServiceProvider());
    $app['event_subscribers'] = function () {
        return [
        ];
    };

    $app->register(new DoctrineServiceProvider());

    $app->register(new DoctrineMigrationCommandsServiceProvider());

    $app->register(new DoctrineCommandsServiceProvider());

    $app->register(new ValidatorServiceProvider());

    $app->register(new OutputBuilderServiceProvider());
}
