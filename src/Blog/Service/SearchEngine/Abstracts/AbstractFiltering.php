<?php

namespace Blog\Service\SearchEngine\Abstracts;

use Blog\Service\SearchEngine\Interfaces\FilteringInterface;

class AbstractFiltering implements FilteringInterface
{
    public function __construct(array $values = [])
    {
        foreach ($values as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }
}
