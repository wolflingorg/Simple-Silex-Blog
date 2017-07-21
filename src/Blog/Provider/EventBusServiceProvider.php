<?php

namespace Blog\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\CallableResolver\CallableCollection;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Name\ClassBasedNameResolver;
use SimpleBus\Message\Subscriber\NotifiesMessageSubscribersMiddleware;
use SimpleBus\Message\Subscriber\Resolver\NameBasedMessageSubscriberResolver;

class EventBusServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritdoc
     */
    public function register(Container $app)
    {
        $app['event_bus'] = function ($app) {
            $bus = new MessageBusSupportingMiddleware();
            $bus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());

            $eventSubscriberCollection = new CallableCollection(
                $app['event_subscribers'],
                new ServiceLocatorAwareCallableResolver(
                    function ($serviceId) use ($app) {
                        return $app[$serviceId];
                    }
                )
            );

            $eventSubscribersResolver = new NameBasedMessageSubscriberResolver(
                new ClassBasedNameResolver(),
                $eventSubscriberCollection
            );

            $bus->appendMiddleware(
                new NotifiesMessageSubscribersMiddleware(
                    $eventSubscribersResolver
                )
            );

            return $bus;
        };

        $app['event_subscribers'] = function () {
            return [];
        };
    }
}
