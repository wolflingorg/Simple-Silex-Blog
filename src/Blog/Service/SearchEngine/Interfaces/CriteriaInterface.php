<?php

namespace Blog\Service\SearchEngine\Interfaces;

interface CriteriaInterface
{
    public function getResourceName();

    public function getFiltering(): FilteringInterface;

    public function getOrdering(): OrderingInterface;

    public function getPaginating(): PaginatingInterface;
}
