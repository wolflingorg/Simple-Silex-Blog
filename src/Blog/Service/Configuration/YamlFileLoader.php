<?php
namespace Blog\Service\Configuration;

use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlFileLoader extends FileLoader
{
    private $configurationBuilder;

    public function __construct(FileLocatorInterface $locator, ConfigurationBuilder $configurationBuilder)
    {
        $this->configurationBuilder = $configurationBuilder;

        parent::__construct($locator);
    }

    public function load($resource, $type = null)
    {
        $paths = $this->locator->locate($resource, null, false);

        if (is_array($paths)) {
            foreach ($paths as $path) {
                $this->processFile($path);
            }
        } else {
            $this->processFile($paths);
        }
    }

    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }

    protected function loadFile($file)
    {
        if (!class_exists('Symfony\Component\Yaml\Parser')) {
            throw new \RuntimeException('Unable to load YAML config files as the Symfony Yaml Component is not installed.');
        }

        if (!stream_is_local($file)) {
            throw new \InvalidArgumentException(sprintf('This is not a local file "%s".', $file));
        }

        if (!file_exists($file)) {
            throw new \InvalidArgumentException(sprintf('The service file "%s" is not valid.', $file));
        }

        try {
            $content = Yaml::parse(file_get_contents($file));
        } catch (ParseException $e) {
            throw new \InvalidArgumentException(sprintf('The file "%s" does not contain valid YAML.', $file), 0, $e);
        }

        return $content;
    }

    private function parseImports(array $content, $file)
    {
        if (!isset($content['imports'])) {
            return;
        }

        if (!is_array($content['imports'])) {
            throw new \InvalidArgumentException(sprintf('The "imports" key should contain an array in %s. Check your YAML syntax.', $file));
        }

        $defaultDirectory = dirname($file);
        foreach ($content['imports'] as $import) {
            if (!is_array($import)) {
                throw new \InvalidArgumentException(sprintf('The values in the "imports" key should be arrays in %s. Check your YAML syntax.', $file));
            }

            $this->setCurrentDir($defaultDirectory);
            $this->import($import['resource'], null, isset($import['ignore_errors']) ?? (bool) $import['ignore_errors'], $file);
        }
    }

    private function processFile($path)
    {
        $content = $this->loadFile($path);
        $this->configurationBuilder->addResource(new FileResource($path));

        // empty file
        if (null === $content) {
            return;
        }

        // imports
        $this->parseImports($content, $path);
        unset($content['imports']);

        // parameters
        if (isset($content['parameters'])) {
            if (!is_array($content['parameters'])) {
                throw new \InvalidArgumentException(sprintf('The "parameters" key should contain an array in %s. Check your YAML syntax.', $path));
            }

            $this->configurationBuilder->mergeParameters($content['parameters']);
        }
        unset($content['parameters']);

        $this->configurationBuilder->mergeConfiguration($content);
    }
}