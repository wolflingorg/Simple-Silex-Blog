<?php

namespace Blog\CommandBus\Command;

class AbstractCommand
{
    /**
     * Applying user query to the command parameters
     *
     * @param array $values user query
     */
    public function __construct(array $values = [])
    {
        foreach ($values as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }
}
