<?php
namespace app;

use Silex\Application;
use Blog\Provider\ConfigurationServiceProvider;
use Blog\Provider\RoutingServiceProvider;

function application($debug = false) : Application
{
    $rootDir = __DIR__;
    $varDir = dirname($rootDir) . DIRECTORY_SEPARATOR . 'var';

    $parameters = [
        'kernel.root_dir'       => $rootDir,
        'kernel.var_dir'        => $varDir,
        'kernel.cache_dir'      => $varDir . DIRECTORY_SEPARATOR . 'cache',
        'kernel.runtime_dir'    => $varDir . DIRECTORY_SEPARATOR . 'runtime',
        'kernel.logs_dir'       => $varDir . DIRECTORY_SEPARATOR . 'logs',
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