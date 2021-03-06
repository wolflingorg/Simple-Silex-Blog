<?php

namespace Blog\Provider;

use Blog\CommandBus\Middleware\Validation\CommandValidationMiddleware;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;

class CommandBusMiddlewareServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritdoc
     */
    public function register(Container $app)
    {
        $app->extend('command_bus', function (MessageBusSupportingMiddleware $bus, Container $app) {
            $bus->prependMiddleware(new CommandValidationMiddleware($app['validator']));

            return $bus;
        });
    }
}
