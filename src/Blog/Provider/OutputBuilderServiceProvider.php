<?php

namespace Blog\Provider;

use Blog\Service\Output\OutputBuilder;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class OutputBuilderServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        // TODO implement different types of response and serialisation schemas
        $app['output_builder'] = function () {
            return new OutputBuilder();
        };
    }
}
