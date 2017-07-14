<?php

namespace Blog\Service\SearchEngine\Interfaces;

interface SearchEngineInterface
{
    public function match(CriteriaInterface $criteria): ResultInterface;
}
