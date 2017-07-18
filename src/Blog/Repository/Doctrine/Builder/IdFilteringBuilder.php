<?php

namespace Blog\Repository\Doctrine\Builder;

use Blog\Repository\Interfaces\CriteriaInterface;
use Doctrine\ORM\QueryBuilder;

class IdFilteringBuilder extends AbstractBuilder
{

    public function supports(CriteriaInterface $criteria): bool
    {
        return isset($criteria->getFiltering()['id']);
    }

    public function build(CriteriaInterface $criteria, QueryBuilder $queryBuilder)
    {
        $alias = AbstractBuilder::getTableAliasByCriteria($criteria);

        $queryBuilder->andWhere("{$alias}.id = :id");
        $queryBuilder->setParameter(':id', $criteria->getFiltering()['id']);
    }
}
