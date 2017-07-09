<?php

namespace Blog\Repository\Interfaces;

use Blog\Repository\Filter\FilterInterface;

interface RepositoryInterface
{
    public function findByPk($id);

    public function findByFilter(FilterInterface $filter);
}
