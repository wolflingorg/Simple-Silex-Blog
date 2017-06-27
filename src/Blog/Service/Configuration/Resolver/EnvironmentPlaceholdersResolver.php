<?php

namespace Blog\Service\Configuration\Resolver;

class EnvironmentPlaceholdersResolver extends AbstractResolver
{
    public function getCollection(): array
    {
        return $this->resolve($this->collection->getCollection());
    }

    protected function resolve(array $collection): array
    {
        $callback = function ($param) {
            return getenv($param) ? getenv($param) : $param;
        };

        array_walk_recursive(
            $collection,
            function (&$val) use ($callback) {
                if (preg_match_all('/%env\(([^\)]+)\)%/', $val, $matches, PREG_PATTERN_ORDER)) {
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