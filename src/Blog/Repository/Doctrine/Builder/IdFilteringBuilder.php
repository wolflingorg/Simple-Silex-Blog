<?php

namespace Blog\Repository\Doctrine\Builder;

use Blog\Repository\Doctrine\Interfaces\BuilderInterface;
use Blog\Repository\Interfaces\CriteriaInterface;
use Doctrine\ORM\QueryBuilder;

class IdFilteringBuilder implements BuilderInterface
{

    /**
     * @inheritdoc
     */
    public function supports(CriteriaInterface $criteria): bool
    {
        return isset($criteria->getFiltering()['id']);
    }

    /**
     * @inheritdoc
     */
    public function build(CriteriaInterface $criteria, QueryBuilder $queryBuilder)
    {
        $alias = $queryBuilder->getRootAliases()[0];

        $queryBuilder->andWhere("{$alias}.id = :id");
        $queryBuilder->setParameter(':id', $criteria->getFiltering()['id']);
    }
}
