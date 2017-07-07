<?php

namespace Blog\Repository;

use Blog\Entity\Post;
use Blog\Repository\Interfaces\PostRepositoryInterface;
use Doctrine\DBAL\Connection;

class PostRepository implements PostRepositoryInterface
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

        // TODO for testing travis errors
        try {
            $this->db->insert(self::TABLE_NAME, $post->toArray(), self::TABLE_TYPES);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
