<?php

namespace Blog\Repository\Doctrine\Interfaces;

use Blog\Repository\Interfaces\CriteriaInterface;
use Doctrine\ORM\QueryBuilder;

interface BuilderInterface
{
    /**
     * Check is this builder supports given criteria
     *
     * @param CriteriaInterface $criteria
     *
     * @return bool
     */
    public function supports(CriteriaInterface $criteria): bool;

    /**
     * Apply criteria to query builder
     *
     * @param CriteriaInterface $criteria
     * @param QueryBuilder $queryBuilder
     *
     * @return mixed
     */
    public function build(CriteriaInterface $criteria, QueryBuilder $queryBuilder);
}
