<?php

namespace app;

use Blog\CommandBus\Command\CreatePostCommand;
use Blog\CommandBus\Handler\CreatePostCommandHandler;
use Blog\Entity\Post;
use Blog\Entity\User;
use Blog\EventBus\Event\PostWasCreatedEvent;
use Blog\Provider\CommandBusMiddlewareServiceProvider;
use Blog\Provider\CommandBusServiceProvider;
use Blog\Provider\DoctrineCommandsServiceProvider;
use Blog\Provider\DoctrineMigrationCommandsServiceProvider;
use Blog\Provider\EventBusServiceProvider;
use Blog\Provider\FixtureCommandsServiceProvider;
use Blog\Provider\JMSSerializerServiceProvider;
use Blog\Provider\OutputBuilderServiceProvider;
use Blog\Repository\Manager\RepositoryManager;
use Blog\Repository\PostRepository;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\ValidatorServiceProvider;

function services(Application $app)
{
    // TODO replace this with the security provider and user provider
    $app['user'] = new User('ab5763c9-1d8c-4ad7-b22e-c484c26973d3');

    // command bus
    $app->register(new CommandBusServiceProvider());
    $app->register(new CommandBusMiddlewareServiceProvider());
    $app['command_handlers'] = function () {
        return [
            CreatePostCommand::class => 'command_bus_create_post_command_handler'
        ];
    };
    $app['command_bus_create_post_command_handler'] = function ($app) {
        return new CreatePostCommandHandler($app['repository_manager'], $app['user'], $app['event_bus']);
    };

    // event bus
    $app->register(new EventBusServiceProvider());
    $app['event_subscribers'] = function () {
        return [
            PostWasCreatedEvent::class => [],
        ];
    };

    // repositories
    $app['repository_manager'] = function ($app) {
        return new RepositoryManager([
            Post::class => $app['post_repository'],
        ]);
    };
    $app['post_repository'] = function ($app) {
        return new PostRepository($app['db']);
    };

    // other
    $app->register(new DoctrineServiceProvider());
    $app->register(new DoctrineMigrationCommandsServiceProvider());
    $app->register(new DoctrineCommandsServiceProvider());
    $app->register(new ValidatorServiceProvider());
    $app->register(new OutputBuilderServiceProvider());
    $app->register(new JMSSerializerServiceProvider());

    // dev environment
    if (in_array($app['environment'], ['DEV', 'TEST'])) {
        $app->register(new FixtureCommandsServiceProvider());
    }
}
