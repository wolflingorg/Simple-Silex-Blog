<?php

namespace Blog\Provider;

use Blog\Service\Output\OutputBuilder;
use Blog\Service\Output\ResponseListener;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class OutputBuilderServiceProvider implements ServiceProviderInterface, EventListenerProviderInterface
{
    public function register(Container $app)
    {
        // TODO implement different serialisation schemas
        $app['output_builder'] = function () {
            return new OutputBuilder();
        };
    }

    public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addSubscriber(
            new ResponseListener($app['output_builder'])
        );
    }
}
