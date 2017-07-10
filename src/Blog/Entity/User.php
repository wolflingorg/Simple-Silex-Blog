<?php

namespace Blog\Entity;

use Blog\Entity\ValueObject\Uuid;

class User
{
    private $id;

    private $name;

    public function __construct(Uuid $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function __toString()
    {
        return sprintf('%s', $this->id);
    }
}
