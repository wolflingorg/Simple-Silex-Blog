<?php

namespace Blog\Entity;

use Blog\Entity\ValueObject\Uuid;

class User
{
    private $id;

    private $name;

    /**
     * @param Uuid $id
     */
    public function __construct(Uuid $id)
    {
        $this->id = $id;
    }

    /**
     * @return Uuid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s', $this->id);
    }
}
