<?php
namespace Blog\Provider;

use Blog\Service\Configuration\Configuration;
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
        $app['config'] = function () {
            $configuration = new Configuration();

            $locator = new FileLocator($this->paths);
            $loaderResolver = new LoaderResolver(array(new YamlFileLoader($locator, $configuration)));
            $delegatingLoader = new DelegatingLoader($loaderResolver);

            $cachePath = $this->parameters['kernel.cache_dir'] . DIRECTORY_SEPARATOR . 'configuration.obj';
            $configMatcherCache = new ConfigCache($cachePath, $this->parameters['kernel.debug']);

            if (!$configMatcherCache->isFresh()) {
                $configuration->mergeParameters($this->parameters);
                $delegatingLoader->load('config.yml');

                $configMatcherCache->write(serialize($configuration), $configuration->getResources());
            } else {
                $configuration = unserialize(file_get_contents($cachePath));
            }

            return $configuration;
        };
    }
}