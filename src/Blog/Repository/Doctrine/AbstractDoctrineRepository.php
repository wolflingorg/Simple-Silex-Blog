<?php

namespace Blog\Repository\Doctrine;

use Blog\Repository\Doctrine\Interfaces\BuilderInterface;
use Blog\Repository\Interfaces\CriteriaInterface;
use Blog\Repository\Interfaces\RepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Parameter;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractDoctrineRepository extends EntityRepository implements RepositoryInterface
{
    protected $builders = [];

    protected $rowCount = 0;

    /**
     * @param BuilderInterface[] $builders
     */
    public function setBuilders(array $builders)
    {
        foreach ($builders as $builder) {
            $this->addBuilder($builder);
        }
    }

    /**
     * @param BuilderInterface $builder
     *
     * @return $this
     */
    public function addBuilder(BuilderInterface $builder)
    {
        $this->builders[] = $builder;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function persist($object)
    {
        $this->getEntityManager()->persist($object);
    }

    /**
     * @inheritdoc
     */
    public function match(CriteriaInterface $criteria)
    {
        $entityName = $criteria->getEntityName();
        $alias = self::getTableAliasByCriteria($criteria);

        $queryBuilder = $this->getEntityManager()->getRepository($entityName)->createQueryBuilder($alias);

        foreach ($this->builders as $builder) {
            if (true === $builder->supports($criteria)) {
                $builder->build($criteria, $queryBuilder);
            }
        }

        $this->setRowCount($queryBuilder);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Return table alias using Criteria entity name
     *
     * @param CriteriaInterface $criteria
     *
     * @return string
     */
    static public function getTableAliasByCriteria(CriteriaInterface $criteria)
    {
        $entityName = $criteria->getEntityName();

        return mb_strtolower(substr(strrchr($entityName, "\\"), 1, 1));
    }

    /**
     * @param QueryBuilder $queryBuilder
     */
    private function setRowCount(QueryBuilder $queryBuilder)
    {
        $subSql = $queryBuilder->getQuery()->getSql();
        $sql = "SELECT count(*) AS count FROM ({$subSql}) as sub_query";

        $parameters = [];
        $types = [];
        foreach ($queryBuilder->getParameters()->toArray() as $parameter) {
            /** @var Parameter $parameter */
            $parameters[] = $parameter->getValue();
            $types[] = $parameter->getType();
        }
        $result = $this->getEntityManager()->getConnection()->fetchAssoc($sql, $parameters, $types);

        $this->rowCount = $result['count'] ?? 0;
    }

    /**
     * @inheritdoc
     */
    public function getRowCount()
    {
        return $this->rowCount;
    }
}
