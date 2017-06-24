<?php
use Silex\Application;
use Blog\Provider\ConfigurationServiceProvider;
use Blog\Provider\RoutingServiceProvider;

function application($debug = false) : Application
{
    $parameters = [
        'kernel.root_dir'       => __DIR__,
        'kernel.var_dir'        => __DIR__ . DIRECTORY_SEPARATOR . 'var',
        'kernel.cache_dir'      => __DIR__ . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'cache',
        'kernel.runtime_dir'    => __DIR__ . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'runtime',
        'kernel.logs_dir'       => __DIR__ . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'logs',
    ];

    $app = new Application([
        'debug' => $debug,
        'environment'    => getenv('APP_ENV'),
    ]);

    // parse configuration
    $app->register(new ConfigurationServiceProvider(
        [
            $parameters['kernel.root_dir'] . DIRECTORY_SEPARATOR . 'config',
        ],
        $parameters
    ));

    // register routing
    $app->register(new RoutingServiceProvider(
        [
            $parameters['kernel.root_dir'] . DIRECTORY_SEPARATOR . 'config',
        ]
    ));

    // register services
    services($app);

    return $app;
}