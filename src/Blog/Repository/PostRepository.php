<?php

namespace Blog\Repository;

use Blog\Entity\Post;
use Blog\Repository\Filter\FilterInterface;
use Blog\Repository\Interfaces\RepositoryInterface;
use Doctrine\DBAL\Connection;

class PostRepository implements RepositoryInterface
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

    public function findByPk($id)
    {
        // TODO: Implement findByPk() method.
    }

    public function findByFilter(FilterInterface $filter)
    {
        // TODO: Implement findByFilter() method.
    }
}
