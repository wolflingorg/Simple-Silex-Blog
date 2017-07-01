<?php

namespace Blog\Provider;

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
            $cachePath = $app['parameters']['kernel.cache_dir'] . DIRECTORY_SEPARATOR . 'configuration.obj';
            $configMatcherCache = new ConfigCache($cachePath, $app['debug']);

            if (!$configMatcherCache->isFresh()) {
                $collection = $app['config_resolver'];
                $config = $collection->getCollection();

                $configMatcherCache->write(serialize($config), $collection->getResources());
            } else {
                $config = unserialize(file_get_contents($cachePath));
            }

            return $config;
        };

        $app['config_loader'] = function ($app) {
            $locator = new FileLocator($app['parameters']['app.config_dirs']);
            $loaderResolver = new LoaderResolver(array(new YamlFileLoader($locator)));
            $delegatingLoader = new DelegatingLoader($loaderResolver);

            return $delegatingLoader->load('config.yml');
        };

        $app['config_resolver'] = function ($app) {
            $collection = new ConfigurationCollection(['parameters' => $app['parameters']]);
            $app['config_loader']->addCollection($collection);

            return new ParameterPlaceholdersResolver(new EnvironmentPlaceholdersResolver($app['config_loader']));
        };

        // Appending config directly to the Container
        foreach ($app['config'] as $key => $value) {
            $app->offsetSet($key, $value);
        }

        // Deleting helper closures
        $app->offsetUnset('config');
        $app->offsetUnset('config_loader');
        $app->offsetUnset('config_resolver');
    }
}