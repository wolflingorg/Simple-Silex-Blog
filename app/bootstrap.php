<?php

namespace app;

use Blog\Provider\ConfigurationServiceProvider;
use Blog\Provider\ConsoleServiceProvider;
use Blog\Provider\RoutingServiceProvider;
use Silex\Application;

function application(): Application
{
    $rootDir = __DIR__;
    $varDir = dirname($rootDir) . DIRECTORY_SEPARATOR . 'var';
    $environment = getenv('APP_ENV');

    $parameters = [
        'kernel.root_dir' => $rootDir,
        'kernel.var_dir' => $varDir,
        'kernel.cache_dir' => $varDir . DIRECTORY_SEPARATOR . 'cache',
        'kernel.runtime_dir' => $varDir . DIRECTORY_SEPARATOR . 'runtime',
        'kernel.logs_dir' => $varDir . DIRECTORY_SEPARATOR . 'logs',
    ];

    $app = new Application([
        'debug' => in_array($environment, ['DEV', 'TEST']),
        'environment' => $environment,
    ]);

    // parse configuration
    $app->register(new ConfigurationServiceProvider(),
        [
            'private.config' => $parameters,
            'private.config.paths' => [
                $parameters['kernel.root_dir'] . DIRECTORY_SEPARATOR . 'config'
            ]
        ]
    );

    // register routing
    $app->register(new RoutingServiceProvider(),
        [
            'private.routing.paths' => [
                $parameters['kernel.root_dir'] . DIRECTORY_SEPARATOR . 'config'
            ]
        ]
    );

    // register console
    $app->register(new ConsoleServiceProvider());

    // register services
    services($app);

    return $app;
}