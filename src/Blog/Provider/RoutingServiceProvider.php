<?php

namespace Blog\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

class RoutingServiceProvider implements ServiceProviderInterface
{
    /**
     * @inheritdoc
     */
    public function register(Container $app)
    {
        $app->extend('routes', function (RouteCollection $routes, Container $app) {
            $cachePath = $app['parameters']['kernel.cache_dir'] . DIRECTORY_SEPARATOR . 'routing' . DIRECTORY_SEPARATOR . 'routing.obj';
            $configMatcherCache = new ConfigCache($cachePath, $app['debug']);

            if (!$configMatcherCache->isFresh()) {
                $collection = $app['routing_loader'];
                $configMatcherCache->write(serialize($collection), $collection->getResources());
            } else {
                $collection = unserialize(file_get_contents($cachePath));
            }

            $routes->addCollection($collection);

            return $routes;
        });

        $app['routing_loader'] = function ($app) {
            $locator = new FileLocator($app['routing']['directory']);
            $loaderResolver = new LoaderResolver(array(new YamlFileLoader($locator)));
            $delegatingLoader = new DelegatingLoader($loaderResolver);

            return $delegatingLoader->load('routing.yml');
        };
    }
}
