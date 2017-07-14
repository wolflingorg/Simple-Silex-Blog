<?php

namespace Blog\Service\SearchEngine\DBAL;

use Blog\Service\SearchEngine\Interfaces\ResultInterface;
use Doctrine\DBAL\Query\QueryBuilder;

class Result implements ResultInterface
{
    private $builder;

    public function __construct(QueryBuilder $builder)
    {
        $this->builder = $builder;
    }
}
