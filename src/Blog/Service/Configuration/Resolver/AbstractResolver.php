<?php

namespace Blog\Service\Configuration\Resolver;

use Blog\Service\Configuration\ConfigurationCollection;
use Blog\Service\Configuration\ConfigurationCollectionInterface;
use Symfony\Component\Config\Resource\ResourceInterface;

abstract class AbstractResolver implements ConfigurationCollectionInterface
{
    protected $collection;

    public function __construct(ConfigurationCollectionInterface $collection)
    {
        $this->collection = $collection;
    }

    public function addCollection(ConfigurationCollection $collection)
    {
        throw new \LogicException('You can not addCollection to the Configuration Resolver Decorator');
    }

    public function getResources()
    {
        return $this->collection->getResources();
    }

    public function addResource(ResourceInterface $resource)
    {
        throw new \LogicException('You can not addResource to the Configuration Resolver Decorator');
    }

    abstract protected function resolve(array $collection): array;
}