<?php

namespace Blog\Service\SearchEngine\Interfaces;

use Blog\Repository\Interfaces\CriteriaInterface;

interface SearchEngineInterface
{
    public function match(CriteriaInterface $criteria): SearchResultInterface;
}
