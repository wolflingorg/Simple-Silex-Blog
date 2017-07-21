<?php

namespace Blog\Service\SearchEngine\Interfaces;

use Blog\Repository\Interfaces\CriteriaInterface;

interface SearchEngineInterface
{
    public function match(CriteriaInterface $criteria): SearchResultInterface;

    public function before(callable $callable, $priority = 0);

    public function after(callable $callable, $priority = 0);
}
