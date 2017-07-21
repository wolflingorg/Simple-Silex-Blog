<?php

namespace Blog\Service\Configuration;

use Symfony\Component\Config\Resource\ResourceInterface;

interface ConfigurationCollectionInterface
{
    /**
     * Merges configuration
     *
     * @param ConfigurationCollectionInterface $collection
     *
     * @return mixed
     */
    public function addCollection(ConfigurationCollectionInterface $collection);

    /**
     * Returns configuration
     *
     * @return array
     */
    public function getCollection(): array;

    /**
     * Return paths to all configuration files
     * that were used during parsing a config file and processing imports
     *
     * @return mixed
     */
    public function getResources();

    /**
     * Add resource
     *
     * @param ResourceInterface $resource
     *
     * @return mixed
     */
    public function addResource(ResourceInterface $resource);
}