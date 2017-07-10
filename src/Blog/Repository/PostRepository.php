<?php

namespace Blog\Repository;

use Blog\Entity\Post;
use Blog\Repository\Filter\FilterInterface;
use Blog\Repository\Interfaces\RepositoryInterface;
use Blog\Repository\Type\UuidType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\PropertyAccess\PropertyAccess;

class PostRepository implements RepositoryInterface
{
    private $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function createPost(Post $post): int
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $values = [];
        foreach (array_keys($this->getTableSchema()) as $key) {
            $values[$key] = $accessor->getValue($post, $key);
        }

        return $this->db->insert($this->getTableName(), $values, $this->getTableSchema());
    }

    protected function getTableSchema()
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

    protected function getTableName()
    {
        return 'post';
    }

    public function findByPk($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM ' . $this->getTableName() . ' WHERE id IN (?)');
        $stmt->bindValue(1, $id, Connection::PARAM_STR_ARRAY);
        $stmt->execute();

        $result = [];
        $accessor = PropertyAccess::createPropertyAccessor();

        foreach ($stmt->fetchAll() as $item) {
            array_walk($item, function (&$value, $key) {
                $value = $this->db->convertToPHPValue($value, $this->getTableSchema()[$key]);
            });

            $post = new Post($item['id'], $item['user']);
            unset($item['id']);
            unset($item['user']);
            foreach ($item as $key => $value) {
                $accessor->setValue($post, $key, $value);
            }

            array_push($result, $post);
        }

        print_r($result);
    }

    public function findByFilter(FilterInterface $filter)
    {
        // TODO: Implement findByFilter() method.
    }
}
