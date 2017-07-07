<?php

namespace Blog\Entity;

use Blog\Entity\Interfaces\ArrayableInterface;
use Blog\Entity\Traits\ArrayableTrait;

class Post implements ArrayableInterface
{
    use ArrayableTrait;

    private $id;

    private $title;

    private $body;

    private $isPublished = false;

    private $user;

    private $createdAt;

    private $updatedAt;

    public function __construct($id, User $user)
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

    public function setIsPublished(bool $isPublished): Post
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
