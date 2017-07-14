<?php

namespace Blog\Repository\DBAL;

use Blog\Entity\Post;
use Blog\Repository\DBAL\Type\UuidType;
use Blog\Service\Repository\AbstractDBALRepository;
use Doctrine\DBAL\Types\Type;

class PostRepository extends AbstractDBALRepository
{
    public function createPost(Post $post): int
    {
        return $this->insertObject($post);
    }

    public function findPost($id)
    {
        return $this->findObjectByPk($id, Post::class, ['id', 'user']);
    }

    protected function getTableName(): string
    {
        return 'post';
    }

    protected function getTableSchema(): array
    {
        return [
            'id' => UuidType::UUID,
            'title' => Type::STRING,
            'body' => Type::STRING,
            'is_published' => Type::BOOLEAN,
            'user' => UuidType::UUID,
            'created_at' => Type::DATETIME,
            'updated_at' => Type::DATETIME
        ];
    }
}
