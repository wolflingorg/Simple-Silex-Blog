<?php

namespace Blog\Provider;

use Blog\Service\Configuration\Configuration;
use Blog\Service\Configuration\ConfigurationCollection;
use Blog\Service\Configuration\Loader\YamlFileLoader;
use Blog\Service\Configuration\Resolver\EnvironmentPlaceholdersResolver;
use Blog\Service\Configuration\Resolver\ParameterPlaceholdersResolver;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;

class ConfigurationServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['config'] = function ($app) {
            $cachePath = $app['private.config']['kernel.cache_dir'] . DIRECTORY_SEPARATOR . 'configuration.obj';
            $configMatcherCache = new ConfigCache($cachePath, $app['debug']);

            if (!$configMatcherCache->isFresh()) {
                $collection = $app['private.config_resolver'];
                $config = new Configuration($collection->getCollection());
                $configMatcherCache->write(serialize($config), $collection->getResources());
            } else {
                $config = unserialize(file_get_contents($cachePath));
            }

            return $config;
        };

        $app['private.config_loader'] = function ($app) {
            $locator = new FileLocator($app['private.config.paths']);
            $loaderResolver = new LoaderResolver(array(new YamlFileLoader($locator)));
            $delegatingLoader = new DelegatingLoader($loaderResolver);

            return $delegatingLoader->load('config.yml');
        };

        $app['private.config_resolver'] = function ($app) {
            $collection = new ConfigurationCollection(['parameters' => $app['private.config']]);
            $app['private.config_loader']->addCollection($collection);

            return new ParameterPlaceholdersResolver(new EnvironmentPlaceholdersResolver($app['private.config_loader']));
        };
    }
}