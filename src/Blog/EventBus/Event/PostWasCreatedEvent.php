<?php

namespace Blog\EventBus\Event;

use Blog\Entity\Post;

class PostWasCreatedEvent
{
    private $post;

    /**
     * @param Post $post
     */
    public function __construct(Post $post)
    {

        $this->post = $post;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }
}
