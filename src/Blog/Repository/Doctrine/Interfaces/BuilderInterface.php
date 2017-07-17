<?php

namespace Blog\Repository\Doctrine\Interfaces;

use Blog\Repository\Interfaces\CriteriaInterface;
use Doctrine\ORM\QueryBuilder;

interface BuilderInterface
{
    public function supports(CriteriaInterface $criteria): bool;

    public function build(CriteriaInterface $criteria, QueryBuilder $queryBuilder);
}
