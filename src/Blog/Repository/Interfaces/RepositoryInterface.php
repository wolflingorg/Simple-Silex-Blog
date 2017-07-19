<?php

namespace Blog\Repository\Interfaces;

interface RepositoryInterface
{
    public function persist($object);

    public function match(CriteriaInterface $criteria);

    public function getRowCount();
}
