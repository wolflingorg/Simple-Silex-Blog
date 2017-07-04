<?php

namespace Blog\CommandBus\Command;

class AbstractCommand
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
