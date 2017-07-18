<?php

namespace Blog\Repository\Doctrine\Builder;

use Blog\Repository\Interfaces\CriteriaInterface;
use Doctrine\ORM\QueryBuilder;

class UserFilteringBuilder extends AbstractBuilder
{

    public function supports(CriteriaInterface $criteria): bool
    {
        return isset($criteria->getFiltering()['user']);
    }

    public function build(CriteriaInterface $criteria, QueryBuilder $queryBuilder)
    {
        $alias = AbstractBuilder::getTableAliasByCriteria($criteria);

        $queryBuilder->andWhere("{$alias}.user = :user");
        $queryBuilder->setParameter(':user', $criteria->getFiltering()['user']);
    }
}
