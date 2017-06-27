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
    public function register(Container $app)
    {
        $app['config'] = function ($app) :Configuration {
            $cachePath = $app['config.kernel']['kernel.cache_dir'] . DIRECTORY_SEPARATOR . 'configuration.obj';
            $configMatcherCache = new ConfigCache($cachePath, $app['debug']);

            if (!$configMatcherCache->isFresh()) {
                $config = $app['config_loader'];
                $configMatcherCache->write(serialize($config), $config->getResources());
            } else {
                $config = unserialize(file_get_contents($cachePath));
            }

            return $config;
        };

        $app['config_loader'] = function ($app): Configuration {
            $configurationBuilder = new ConfigurationBuilder();
            $configurationBuilder->mergeParameters($app['config.kernel']);

            $locator = new FileLocator($app['config.paths']);
            $loaderResolver = new LoaderResolver(array(new YamlFileLoader($locator, $configurationBuilder)));
            $delegatingLoader = new DelegatingLoader($loaderResolver);
            $delegatingLoader->load('config.yml');

            return $configurationBuilder->build();
        };
    }
}