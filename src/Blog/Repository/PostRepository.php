<?php

namespace Blog\Repository;

use Blog\Entity\Post;
use Doctrine\DBAL\Connection;

class PostRepository
{
    const TABLE_NAME = 'post';

    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function createPost(Post $post)
    {
        $values = [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'body' => $post->getBody(),
            'is_published' => $post->isPublished(),
            'user' => $post->getUser()->getId(),
            'created_at' => new \DateTime(),
            'updated_at' => new \DateTime(),
        ];

        $this->db->insert(self::TABLE_NAME, $values, [
            'is_published' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ]);
    }
}
