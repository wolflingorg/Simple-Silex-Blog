<?php

namespace Blog\Repository;

use Blog\Repository\Interfaces\RepositoryInterface;
use Doctrine\DBAL\Connection;
use Symfony\Component\PropertyAccess\PropertyAccess;

abstract class AbstractDBALRepository implements RepositoryInterface
{
    protected $db;

    public function __construct(Connection $db)
    {
        $this->db = $db;
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

    abstract protected function getTableSchema(): array;

    protected function insert($object)
    {
        return $this->db->insert($this->getTableName(), $this->objectToArray($object), $this->getTableSchema());
    }

    abstract protected function getTableName(): string;

    protected function objectToArray($object)
    {
        $values = [];

        $accessor = PropertyAccess::createPropertyAccessor();
        foreach (array_keys($this->getTableSchema()) as $key) {
            $values[$key] = $accessor->getValue($object, $key);
        }

        return $values;
    }
}
