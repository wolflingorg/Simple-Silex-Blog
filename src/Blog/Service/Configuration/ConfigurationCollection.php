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
     * @inheritdoc
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
     * @inheritdoc
     */
    public function getCollection(): array
    {
        return $this->collection;
    }

    /**
     * @inheritdoc
     */
    public function getResources()
    {
        return array_unique($this->resources);
    }

    /**
     * @inheritdoc
     */
    public function addResource(ResourceInterface $resource)
    {
        $this->resources[] = $resource;
    }
}