<?php

namespace Blog\Repository\Doctrine\Builder;

use Blog\Repository\Doctrine\Interfaces\BuilderInterface;
use Blog\Repository\Interfaces\CriteriaInterface;
use Doctrine\ORM\QueryBuilder;

class UserFilteringBuilder implements BuilderInterface
{

    public function supports(CriteriaInterface $criteria): bool
    {
        return isset($criteria->getFiltering()['user']);
    }

    public function build(CriteriaInterface $criteria, QueryBuilder $queryBuilder)
    {
        $alias = $queryBuilder->getRootAliases()[0];

        $queryBuilder->andWhere("{$alias}.user = :user");
        $queryBuilder->setParameter(':user', $criteria->getFiltering()['user']);
    }
}
