<?php

namespace Blog\Service\Configuration\Loader;

use Blog\Service\Configuration\ConfigurationCollection;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlFileLoader extends FileLoader
{
    /**
     * Loading and parsing config file from specific path
     *
     * @param mixed $resource
     * @param null $type
     *
     * @return ConfigurationCollection
     */
    public function load($resource, $type = null)
    {
        $paths = $this->locator->locate($resource, null, false);

        $collection = new ConfigurationCollection();

        foreach ((array)$paths as $path) {
            $content = $this->loadFile($path);
            $collection->addResource(new FileResource($path));

            // empty file
            if (null === $content) {
                continue;
            }

            // imports
            $this->parseImports($content, $path, $collection);

            $collection->addCollection(new ConfigurationCollection($content));
        }

        return $collection;
    }

    /**
     * Trying to read file
     *
     * @param $file
     *
     * @return mixed
     */
    protected function loadFile($file)
    {
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

    /**
     * @param array $content
     * @param $file
     * @param ConfigurationCollection $collection
     */
    private function parseImports(array &$content, $file, ConfigurationCollection $collection)
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
            $subCollection = $this->import($import['resource'], null, isset($import['ignore_errors']) ?? (bool)$import['ignore_errors'], $file);

            $collection->addCollection($subCollection);
        }

        unset($content['imports']);
    }

    /**
     * Check if current resource is supported
     *
     * @param mixed $resource
     * @param null $type
     *
     * @return bool
     */
    public function supports($resource, $type = null)
    {
        return is_string($resource) && 'yml' === pathinfo($resource, PATHINFO_EXTENSION);
    }
}