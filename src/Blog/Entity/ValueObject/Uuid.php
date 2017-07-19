<?php

namespace Blog\Entity\ValueObject;

class Uuid
{
    private $uuid;

    public function __construct(string $uuid)
    {
        if (!preg_match('/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-4[0-9A-Fa-f]{3}-[89ABab][0-9A-Fa-f]{3}-[0-9A-Fa-f]{12}$/', $uuid)) {
            throw new \InvalidArgumentException(sprintf("UUID v4 %s isn't correct", $uuid));
        }

        $this->uuid = $uuid;
    }

    public function __toString()
    {
        return $this->uuid;
    }
}
