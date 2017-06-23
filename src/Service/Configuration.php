<?php
namespace Blog\Service;

use Symfony\Component\Yaml\Yaml;

class Configuration implements \ArrayAccess
{
    private $parameters = [];
    private $configuration = [];

    public function __construct($file, array $parameters = [])
    {
        $this->parameters = $parameters;

        $this->load($file);
    }

    private function load($file)
    {
        $configuration = $this->parseConfig($file);

        // empty file
        if (null === $configuration) {
            return;
        }

        // imports
        $this->parseImports($configuration, $file);

        // merge parameters
        $this->mergeParameters($configuration);

        // replace placeholders
        $this->replacePlaceholders($configuration);

        // merge
        $this->mergeConfiguration($configuration);
    }

    private function parseConfig($file)
    {
        if(file_exists($file)) {
            return Yaml::parse(file_get_contents($file));
        } else {
            throw new \InvalidArgumentException(sprintf('File "%s" does not exists.', $file));
        }
    }

    private function parseImports(&$configuration, $file)
    {
        if (!isset($configuration['imports'])) {
            return;
        }

        if (!is_array($configuration['imports'])) {
            throw new \InvalidArgumentException(sprintf('The "imports" key should contain an array in %s. Check your YAML syntax.', $file));
        }

        $directory = dirname($file);

        foreach ($configuration['imports'] as $import) {
            $this->load($directory . DIRECTORY_SEPARATOR . $import);
        }

        unset($configuration['imports']);
    }

    private function replacePlaceholders(&$configuration)
    {
        $callback = function($param) {
            return isset($this->parameters[$param]) ? $this->parameters[$param] : $param;
        };

        array_walk_recursive(
            $configuration,
            function(&$val) use ($callback) {
                if (preg_match_all('/%([^%]+)%/', $val, $matches, PREG_PATTERN_ORDER)) {
                    $val = str_replace(
                        $matches[0],
                        array_map($callback, $matches[1]),
                        $val
                    );
                }
            }
        );
    }

    private function mergeConfiguration($configuration)
    {
        $this->configuration = array_replace_recursive(
            $this->configuration,
            $configuration
        );
    }

    private function mergeParameters(&$configuration)
    {
        if (!isset($configuration['parameters'])) {
            return;
        }

        if (!is_array($configuration['parameters'])) {
            throw new \InvalidArgumentException(sprintf('The "parameters" key should contain an array. Check your YAML syntax.'));
        }

        $this->parameters = array_replace_recursive(
            $this->parameters,
            $configuration['parameters']
        );

        unset($configuration['parameters']);
    }

    public function offsetExists($offset)
    {
        return 'parameters' === $offset || isset($this->configuration[$offset]);
    }

    public function offsetGet($offset)
    {
        if ($offset === 'parameters') {
            return $this->parameters;
        } elseif (isset($this->configuration[$offset])) {
            return $this->configuration[$offset];
        }

        return null;
    }

    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException('You can not set any parameter to the configuration on the runtime');
    }

    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException('You can not delete any parameter from the configuration on the runtime');
    }
}