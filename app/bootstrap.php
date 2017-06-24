<?php
use Silex\Application;
use \Blog\Provider\ConfigurationServiceProvider;

function application($debug = false) : Application
{
    $app = new Application();

    $parameters = [
        'kernel.root_dir'       => __DIR__,
        'kernel.environment'    => '%env(APP_ENV)%',
        'kernel.debug'          => $debug,
        'kernel.var_dir'        => __DIR__ . DIRECTORY_SEPARATOR . 'var',
        'kernel.cache_dir'      => __DIR__ . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'cache',
        'kernel.runtime_dir'    => __DIR__ . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'runtime',
        'kernel.logs_dir'       => __DIR__ . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'logs',
        'kernel.charset'        => 'UTF-8'
    ];

    // parse configuration
    $app->register(new ConfigurationServiceProvider(
        [
            $parameters['kernel.root_dir'] . DIRECTORY_SEPARATOR . 'config',
        ],
        $parameters
    ));

    // register services
    services($app);

    print_r($app['config']);

    return $app;
}