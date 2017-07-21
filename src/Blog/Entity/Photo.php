<?php

namespace Blog\Entity;

use Blog\Entity\Traits\TimestampableTrait;
use Blog\Entity\ValueObject\Uuid;

class Photo
{
    use TimestampableTrait;

    private $id;

    private $src;

    private $user;

    /**
     * @param Uuid $id
     * @param Uuid $user
     */
    public function __construct(Uuid $id, Uuid $user)
    {
        $this->id = $id;
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * @param $src
     *
     * @return $this
     */
    public function setSrc($src)
    {
        $this->src = $src;

        return $this;
    }

    /**
     * @return Uuid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Uuid
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s', $this->id);
    }
}
