<?php

namespace Blog\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;
use SimpleBus\Message\Handler\Resolver\NameBasedMessageHandlerResolver;
use SimpleBus\Message\Name\ClassBasedNameResolver;

class CommandBusServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['command_bus'] = function ($app) {
            $bus = new MessageBusSupportingMiddleware();
            $bus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());

            $commandHandlerMap = new CallableMap(
                $app['command_handlers'],
                new ServiceLocatorAwareCallableResolver(
                    function ($serviceId) use ($app) {
                        return $app[$serviceId];
                    }
                )
            );

            $commandHandlerResolver = new NameBasedMessageHandlerResolver(
                new ClassBasedNameResolver(),
                $commandHandlerMap
            );

            $bus->appendMiddleware(
                new DelegatesToMessageHandlerMiddleware(
                    $commandHandlerResolver
                )
            );

            return $bus;
        };

        $app['command_handlers'] = function () {
            return [];
        };
    }
}