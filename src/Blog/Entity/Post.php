<?php

namespace Blog\Entity;

use Blog\Entity\Traits\TimestampableTrait;
use Blog\Entity\ValueObject\Uuid;

class Post
{
    use TimestampableTrait;

    private $id;

    private $title;

    private $body;

    private $isPublished = false;

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
     * @return Uuid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param $body
     *
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPublished(): bool
    {
        return $this->isPublished;
    }

    /**
     * @param bool $isPublished
     *
     * @return $this
     */
    public function setIsPublished(bool $isPublished)
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * @return Uuid
     */
    public function getUser()
    {
        return $this->user;
    }
}
