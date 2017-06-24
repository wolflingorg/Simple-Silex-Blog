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
    private $paths;

    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    public function register(Container $app)
    {
        $app['routing'] = $app->extend('routes', function (RouteCollection $routes, Container $app) {
            $locator = new FileLocator($this->paths);
            $loaderResolver = new LoaderResolver(array(new YamlFileLoader($locator)));
            $delegatingLoader = new DelegatingLoader($loaderResolver);

            $cachePath = $app['config']['parameters']['kernel.cache_dir'] . DIRECTORY_SEPARATOR . 'routing.obj';
            $configMatcherCache = new ConfigCache($cachePath, $app['debug']);

            if (!$configMatcherCache->isFresh()) {
                $collection = $delegatingLoader->load('routing.yml');
                $routes->addCollection($collection);

                $configMatcherCache->write(serialize($collection), $routes->getResources());
            } else {
                $collection = unserialize(file_get_contents($cachePath));
            }

            return $collection;
        });
    }
}