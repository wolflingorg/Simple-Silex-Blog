<?php

namespace Blog\Service\Configuration;

use Symfony\Component\Config\Resource\ResourceInterface;

interface ConfigurationCollectionInterface
{
    public function addCollection(ConfigurationCollection $collection);

    public function getCollection(): array;

    public function getResources();

    public function addResource(ResourceInterface $resource);
}