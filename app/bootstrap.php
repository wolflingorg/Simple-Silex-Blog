<?php
use Silex\Application;
use \Blog\Provider\ConfigurationServiceProvider;

function application() : Application
{
    $app = new Application();

    $parameters = [
        'kernel.root_dir'       => __DIR__,
        'kernel.environment'    => getenv('APP_ENV') ?? 'DEV',
        'kernel.config_dir'     => __DIR__ . DIRECTORY_SEPARATOR . 'config',
        'kernel.var_dir'        => __DIR__ . DIRECTORY_SEPARATOR . 'var',
        'kernel.cache_dir'      => __DIR__ . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'cache',
        'kernel.runtime_dir'    => __DIR__ . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'runtime',
        'kernel.logs_dir'       => __DIR__ . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'logs',
        'kernel.charset'        => 'UTF-8'
    ];

    $app->register(new ConfigurationServiceProvider(
        $parameters['kernel.config_dir'] . DIRECTORY_SEPARATOR . 'config.yml',
        $parameters
    ));

    print_r($app['configuration']);

    return $app;
}