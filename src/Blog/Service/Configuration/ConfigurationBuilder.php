<?php
namespace Blog\Service\Configuration;

use Symfony\Component\Config\Resource\ResourceInterface;

class ConfigurationBuilder
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
     * Builds configuration
     *
     * @return Configuration
     */
    public function build() :Configuration
    {
        $this->replaceEnvironmentPlaceholders();
        $this->replaceParameterPlaceholders();

        return new Configuration(
            array_merge(
                $this->configuration,
                [
                    'parameters' => $this->parameters
                ]
            ),
            $this->resources
        );
    }

    /**
     * Replaces env variables in parameters
     */
    private function replaceEnvironmentPlaceholders() {
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
    }

    /**
     * Replaces placeholders in configuration with parameter value
     */
    private function replaceParameterPlaceholders() {
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