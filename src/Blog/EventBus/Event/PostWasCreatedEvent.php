<?php

namespace Blog\EventBus\Event;

use Blog\Entity\Post;

class PostWasCreatedEvent
{
    private $post;

    public function __construct(Post $post)
    {

        $this->post = $post;
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
