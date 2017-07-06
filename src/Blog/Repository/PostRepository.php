<?php

namespace Blog\Repository;

use Blog\Entity\Post;
use Doctrine\DBAL\Connection;

class PostRepository
{
    const TABLE_NAME = 'post';
    const TABLE_TYPES = [
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'user' => 'string'
    ];

    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function createPost(Post $post)
    {
        $post
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());

        $this->db->insert(self::TABLE_NAME, $post->toArray(), self::TABLE_TYPES);
    }
}
