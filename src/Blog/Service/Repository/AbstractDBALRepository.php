<?php

namespace Blog\Service\Repository;

use Blog\Service\Repository\Interfaces\RepositoryInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class AbstractDBALRepository implements RepositoryInterface
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
    }

    public function insert($data)
    {
        return $this->db->insert($this->getTableName(), $data, $this->getTableSchema());
    }

    abstract protected function getTableName(): string;

    abstract protected function getTableSchema(): array;

    public function insertObject($object)
    {
        return $this->db->insert($this->getTableName(), $this->objectToArray($object), $this->getTableSchema());
    }

    protected function objectToArray($object)
    {
        $values = [];

        $accessor = PropertyAccess::createPropertyAccessor();
        foreach (array_keys($this->getTableSchema()) as $key) {
            $values[$key] = $accessor->getValue($object, $key);
        }

        return $values;
    }

    public function findByCriteria($criteria)
    {
        // TODO: Implement findByCriteria() method.
    }

    public function findObjectByPk($id, $class, $ctor_args = [])
    {
        $result = $this->findByPk($id);

        return !empty($result) ? $this->arrayToObject($result, $class, $ctor_args) : null;
    }

    public function findByPk($id)
    {
        $sql = 'SELECT * FROM ' . $this->getTableName() . ' WHERE id IN (?)';
        $result = $this->db->fetchAll($sql, [$id], [Type::STRING]);

        return !empty($result) ? $result[0] : null;
    }

    protected function arrayToObject(array $array, string $class, $ctor_args = [])
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class %s is not exists', $class));
        }

        array_walk($array, function (&$value, $key) {
            $value = $this->db->convertToPHPValue($value, $this->getTableSchema()[$key]);
        });

        if (!empty($ctor_args)) {
            $ctor = array_values(array_filter($array, function ($key) use ($ctor_args) {
                return in_array($key, $ctor_args);
            }, ARRAY_FILTER_USE_KEY));

            if (sizeof($ctor_args) !== sizeof($ctor)) {
                throw new \InvalidArgumentException('Not enough parameters for constructor');
            }
        }

        $object = !empty($ctor) ? new $class(...$ctor) : new $class;

        $accessor = PropertyAccess::createPropertyAccessor();
        foreach ($array as $key => $value) {
            if ($accessor->isWritable($object, $key)) {
                $accessor->setValue($object, $key, $value);
            }
        }

        return $object;
    }

    public function save($id, $data)
    {
        // TODO: Implement save() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}
