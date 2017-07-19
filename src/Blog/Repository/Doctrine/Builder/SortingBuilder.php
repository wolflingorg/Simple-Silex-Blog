<?php

namespace Blog\Repository\Doctrine\Builder;

use Blog\Repository\Doctrine\Interfaces\BuilderInterface;
use Blog\Repository\Interfaces\CriteriaInterface;
use Doctrine\ORM\QueryBuilder;

class SortingBuilder implements BuilderInterface
{

    public function supports(CriteriaInterface $criteria): bool
    {
        return !empty($criteria->getSorting());
    }

    public function build(CriteriaInterface $criteria, QueryBuilder $queryBuilder)
    {
        $alias = $queryBuilder->getRootAliases()[0];

        foreach ($criteria->getSorting() as $sort => $order) {
            if (!is_null($order)) {
                $queryBuilder->addOrderBy("{$alias}.{$sort}", $order);
            }
        }
    }
}
