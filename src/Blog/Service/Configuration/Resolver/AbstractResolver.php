<?php

namespace Blog\Service\Configuration\Resolver;

use Blog\Service\Configuration\ConfigurationCollectionInterface;
use Symfony\Component\Config\Resource\ResourceInterface;

/**
 * Configuration Collection Decorator
 *
 * Config can contain placeholders to parameters or to environment variables
 * Resolvers try to replace these placeholders with the correct values
 *
 * @package Blog\Service\Configuration\Resolver
 */
abstract class AbstractResolver implements ConfigurationCollectionInterface
{
    protected $collection;

    /**
     * @param ConfigurationCollectionInterface $collection
     */
    public function __construct(ConfigurationCollectionInterface $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @inheritdoc
     */
    public function addCollection(ConfigurationCollectionInterface $collection)
    {
        throw new \LogicException('You can not addCollection to the Configuration Resolver Decorator');
    }

    /**
     * @inheritdoc
     */
    public function getResources()
    {
        return $this->collection->getResources();
    }

    /**
     * @inheritdoc
     */
    public function addResource(ResourceInterface $resource)
    {
        throw new \LogicException('You can not addResource to the Configuration Resolver Decorator');
    }

    /**
     * Trying to replace placeholders with the correct values
     *
     * @param array $collection
     *
     * @return array
     */
    abstract protected function resolve(array $collection): array;
}