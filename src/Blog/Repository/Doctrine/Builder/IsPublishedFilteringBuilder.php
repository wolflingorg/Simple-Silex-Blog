<?php

namespace Blog\Repository\Doctrine\Builder;

use Blog\Repository\Doctrine\Interfaces\BuilderInterface;
use Blog\Repository\Interfaces\CriteriaInterface;
use Doctrine\ORM\QueryBuilder;

class IsPublishedFilteringBuilder implements BuilderInterface
{

    public function supports(CriteriaInterface $criteria): bool
    {
        return isset($criteria->getFiltering()['is_published']);
    }

    public function build(CriteriaInterface $criteria, QueryBuilder $queryBuilder)
    {
        $alias = $queryBuilder->getRootAliases()[0];

        $queryBuilder->andWhere("{$alias}.isPublished = :is_published");
        $queryBuilder->setParameter(':is_published', $criteria->getFiltering()['is_published']);
    }
}
