<?php

namespace Blog\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Console\Application;

class ConsoleServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritdoc
     */
    public function register(Container $app)
    {
        $app['console'] = function () {
            return new Application();
        };
    }
}
