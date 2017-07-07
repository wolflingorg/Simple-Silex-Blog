<?php

namespace Blog\Repository\Interfaces;

use Blog\Entity\Post;

interface PostRepositoryInterface
{
    public function createPost(Post $post);
}
