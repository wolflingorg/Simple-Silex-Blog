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

    public function __construct(Uuid $id, Uuid $user)
    {
        $this->id = $id;
        $this->user = $user;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function isPublished(): bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool $isPublished)
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }
}
