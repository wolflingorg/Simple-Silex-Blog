<?php

namespace Blog\Repository\Doctrine\Builder;

use Blog\Repository\Interfaces\CriteriaInterface;
use Doctrine\ORM\QueryBuilder;

class IsPublishedFilteringBuilder extends AbstractBuilder
{

    public function supports(CriteriaInterface $criteria): bool
    {
        return isset($criteria->getFiltering()['is_published']);
    }

    public function build(CriteriaInterface $criteria, QueryBuilder $queryBuilder)
    {
        $alias = AbstractBuilder::getTableAliasByCriteria($criteria);

        $queryBuilder->andWhere("{$alias}.isPublished = :is_published");
        $queryBuilder->setParameter(':is_published', $criteria->getFiltering()['is_published']);
    }
}
