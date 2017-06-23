<?php
namespace Blog\Provider;

use Blog\Service\Configuration;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

class ConfigurationServiceProvider implements ServiceProviderInterface
{
    private $configFile;
    private $parameters;

    public function __construct($configFile, array $parameters = [])
    {
        $this->configFile = $configFile;
        $this->parameters = $parameters;
    }

    public function register(Container $app)
    {
        $app['configuration'] = function () {
            return new Configuration($this->configFile, $this->parameters);
        };
    }
}