<?php

namespace Blog\Repository\DBAL;

use Blog\Entity\Post;
use Blog\QueryPipeline\PostCriteria\PostCriteria;
use Blog\Repository\DBAL\Type\UuidType;
use Blog\Service\Repository\AbstractDBALRepository;
use Doctrine\DBAL\Query\QueryBuilder;
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

    public function findByCriteria($criteria)
    {
        if (!$criteria instanceof PostCriteria) {
            throw new \InvalidArgumentException('Not valid criteria. Expecting instance of CriteriaInterface');
        }

        $queryBuilder = new QueryBuilder($this->db);
        $queryBuilder
            ->select('p.*')
            ->from($this->getTableName(), 'p');


        $sth = $queryBuilder->execute();

        $result = [];
        foreach ($sth->fetchAll() as $item) {
            $result[] = $this->arrayToObject($item, Post::class, ['id', 'user']);
        }

        return $result;
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
            'is_published' => Type::SMALLINT,
            'user' => UuidType::UUID,
            'created_at' => Type::DATETIME,
            'updated_at' => Type::DATETIME
        ];
    }
}
