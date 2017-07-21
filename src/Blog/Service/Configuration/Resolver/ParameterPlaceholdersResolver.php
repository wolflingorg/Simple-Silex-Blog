<?php

namespace Blog\Service\Configuration\Resolver;

/**
 * Replace placeholders with the correct parameter variables
 *
 * @package Blog\Service\Configuration\Resolver
 */
class ParameterPlaceholdersResolver extends AbstractResolver
{
    /**
     * Decorated method
     *
     * @return array
     */
    public function getCollection(): array
    {
        return $this->resolve($this->collection->getCollection());
    }

    /**
     * @param array $collection
     *
     * @return array
     */
    protected function resolve(array $collection): array
    {
        if (!isset($collection['parameters']) || !is_array($collection['parameters'])) {
            return $collection;
        }

        $callback = function ($param) use ($collection) {
            return array_key_exists($param, $collection['parameters']) ? $collection['parameters'][$param] : $param;
        };

        array_walk_recursive(
            $collection,
            function (&$val) use ($callback) {
                if (preg_match_all('/%([^%]+)%/', $val, $matches, PREG_PATTERN_ORDER)) {
                    $val = str_replace(
                        $matches[0],
                        array_map($callback, $matches[1]),
                        $val
                    );
                }
            }
        );

        return $collection;
    }
}
