<?php

namespace Blog\Repository\Doctrine\Builder;

use Blog\Repository\Doctrine\Interfaces\BuilderInterface;
use Blog\Repository\Interfaces\CriteriaInterface;
use Doctrine\ORM\QueryBuilder;

class PaginatingBuilder implements BuilderInterface
{

    public function supports(CriteriaInterface $criteria): bool
    {
        return true;
    }

    public function build(CriteriaInterface $criteria, QueryBuilder $queryBuilder)
    {
        $paginating = $criteria->getPaginating();

        $queryBuilder->setMaxResults($paginating['per_page']);
        $queryBuilder->setFirstResult($paginating['offset']);
    }
}
