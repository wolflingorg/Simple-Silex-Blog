<?php

namespace Blog\Service\SearchEngine\Interfaces;

interface CriteriaInterface
{
    public function getResourceName();

    public function getFiltering();

    public function getSorting();

    public function getPagination();
}
