<?php
namespace Blog\Provider;

use Blog\Service\Configuration\Configuration;
use Blog\Service\Configuration\ConfigurationBuilder;
use Blog\Service\Configuration\YamlFileLoader;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;

class ConfigurationServiceProvider implements ServiceProviderInterface
{
    private $parameters;
    private $paths;

    public function __construct(array $paths, array $parameters = [])
    {
        $this->parameters = $parameters;
        $this->paths = $paths;
    }

    public function register(Container $app)
    {
        $app['config'] = function ($app) :Configuration {
            $cachePath = $this->parameters['kernel.cache_dir'] . DIRECTORY_SEPARATOR . 'configuration.obj';
            $configMatcherCache = new ConfigCache($cachePath, $app['debug']);

            if (!$configMatcherCache->isFresh()) {
                $configurationBuilder = new ConfigurationBuilder();
                $configurationBuilder->mergeParameters($this->parameters);

                $locator = new FileLocator($this->paths);
                $loaderResolver = new LoaderResolver(array(new YamlFileLoader($locator, $configurationBuilder)));

                $delegatingLoader = new DelegatingLoader($loaderResolver);
                $delegatingLoader->load('config.yml');

                $configuration = $configurationBuilder->build();
                $configMatcherCache->write(serialize($configuration), $configurationBuilder->getResources());
            } else {
                $configuration = unserialize(file_get_contents($cachePath));
            }

            return $configuration;
        };
    }
}