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

    $app = new Application([
        'debug' => in_array($environment, ['DEV', 'TEST']),
        'environment' => $environment,
    ]);

    $app['parameters'] = [
        'kernel.root_dir' => $rootDir,
        'kernel.config_dir' => $rootDir . DIRECTORY_SEPARATOR . 'config',
        'kernel.var_dir' => $varDir,
        'kernel.cache_dir' => $varDir . DIRECTORY_SEPARATOR . 'cache',
        'kernel.runtime_dir' => $varDir . DIRECTORY_SEPARATOR . 'runtime',
        'kernel.logs_dir' => $varDir . DIRECTORY_SEPARATOR . 'logs',
    ];

    // parse configuration
    $app->register(new ConfigurationServiceProvider());

    // register routing
    $app->register(new RoutingServiceProvider());

    // register console
    $app->register(new ConsoleServiceProvider());

    // register services
    services($app);

    // middlewares
    middlewares($app);

    // exceptions
    exceptions($app);

    return $app;
}
