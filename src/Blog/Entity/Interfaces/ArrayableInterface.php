<?php

namespace Blog\Entity\Interfaces;

/**
 * Some thoughts about this interface/magic method: https://wiki.php.net/rfc/object_cast_to_types
 */
interface ArrayableInterface
{
    public function toArray(): array;
}
