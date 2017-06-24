<?php
namespace Blog\Service\Configuration;

use Symfony\Component\Config\Resource\ResourceInterface;

class Configuration implements \ArrayAccess
{
    protected $parameters = [];
    protected $configuration = [];
    private $resources = [];

    /**
     * Merges configuration
     *
     * @param array $configuration
     */
    public function mergeConfiguration(array $configuration)
    {
        $this->configuration = array_replace_recursive(
            $this->configuration,
            $configuration
        );

        $this->replacePlaceholders();
    }

    /**
     * Merges parameters
     *
     * @param array $parameters
     */
    public function mergeParameters(array $parameters)
    {
        $this->parameters = array_replace_recursive(
            $this->parameters,
            $parameters
        );
    }

    /**
     * Adds a resource for this configuration
     *
     * @param ResourceInterface $resource
     */
    public function addResource(ResourceInterface $resource)
    {
        $this->resources[] = $resource;
    }

    /**
     * Returns all resources for this configuration
     *
     * @return ResourceInterface[]
     */
    public function getResources()
    {
        return $this->resources;
    }

    /**
     * @inheritdoc
     */
    public function offsetExists($offset)
    {
        return 'parameters' === $offset || isset($this->configuration[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet($offset)
    {
        if ($offset === 'parameters') {
            return $this->parameters;
        } elseif (isset($this->configuration[$offset])) {
            return $this->configuration[$offset];
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException('You can not set any parameter to the configuration on the runtime');
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset($offset)
    {
        throw new \BadMethodCallException('You can not delete any parameter from the configuration on the runtime');
    }

    /**
     * Replaces placeholders with parameter value or environment value
     */
    private function replacePlaceholders()
    {
        // replace env variables in parameters
        $callback = function($param) {
            return getenv($param) ? getenv($param) : $param;
        };

        array_walk_recursive(
            $this->parameters,
            function(&$val) use ($callback) {
                if (preg_match_all('/%env\(([^\)]+)\)%/', $val, $matches, PREG_PATTERN_ORDER)) {
                    $val = str_replace(
                        $matches[0],
                        array_map($callback, $matches[1]),
                        $val
                    );
                }
            }
        );

        // replace placeholders in configuration with parameter value
        $callback = function($param) {
            return isset($this->parameters[$param]) ? $this->parameters[$param] : $param;
        };

        array_walk_recursive(
            $this->configuration,
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
}