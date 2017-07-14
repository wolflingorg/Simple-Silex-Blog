<?php

namespace Blog\Service\SearchEngine\DBAL\Interfaces;

use Blog\Service\SearchEngine\Interfaces\CriteriaInterface;
use Doctrine\DBAL\Query\QueryBuilder;

interface BuilderInterface
{
    public function supports(CriteriaInterface $criteria): bool;

    public function build(CriteriaInterface $criteria, QueryBuilder $queryBuilder);
}
