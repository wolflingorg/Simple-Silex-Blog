<?php

namespace Blog\Repository;

use Blog\Entity\Post;
use Blog\Repository\Filter\FilterInterface;
use Blog\Repository\Type\UuidType;
use Doctrine\DBAL\Types\Type;

class PostRepository extends AbstractDBALRepository
{
    public function createPost(Post $post): int
    {
        return $this->insert($post);
    }

    public function findByPk($id)
    {
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE id IN (?)';
        $result = $this->db->fetchAll($sql, [$id], [Type::STRING]);

        return !empty($result) ? $this->arrayToObject($result[0], Post::class, ['id', 'user']) : null;
    }

    protected function getTableName(): string
    {
        return 'post';
    }

    public function findByFilter(FilterInterface $filter)
    {
        // TODO: Implement findByFilter() method.
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
