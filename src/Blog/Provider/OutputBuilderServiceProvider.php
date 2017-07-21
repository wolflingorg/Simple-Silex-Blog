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
    /**
     * @inheritdoc
     */
    public function register(Container $app)
    {
        $app['output_builder'] = function ($app) {
            return new OutputBuilder($app['serializer']);
        };
    }

    /**
     * @inheritdoc
     */
    public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addSubscriber(
            new ResponseListener($app['output_builder'])
        );
    }
}
