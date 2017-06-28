<?php

namespace Blog\Service\Configuration;

use Symfony\Component\Config\Resource\ResourceInterface;

class ConfigurationCollection implements ConfigurationCollectionInterface
{
    protected $collection = [];

    protected $resources = [];

    public function __construct(array $collection = [])
    {
        $this->collection = $collection;
    }

    /**
     * Merges configuration
     *
     * @param ConfigurationCollectionInterface $collection
     */
    public function addCollection(ConfigurationCollectionInterface $collection)
    {
        $this->collection = array_replace_recursive(
            $this->collection,
            $collection->getCollection()
        );

        $this->resources = array_merge($this->resources, $collection->getResources());
    }

    /**
     * @return array
     */
    public function getCollection(): array
    {
        return $this->collection;
    }

    /**
     * Returns an array of resources loaded to build this collection.
     *
     * @return ResourceInterface[] An array of resources
     */
    public function getResources()
    {
        return array_unique($this->resources);
    }

    /**
     * Adds a resource for this collection.
     *
     * @param ResourceInterface $resource A resource instance
     */
    public function addResource(ResourceInterface $resource)
    {
        $this->resources[] = $resource;
    }
}