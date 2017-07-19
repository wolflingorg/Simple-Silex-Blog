<?php

namespace Blog\Repository\Interfaces;

interface CriteriaInterface
{
    public function getEntityName(): string;

    public function getFiltering(): array;

    public function getPaginating(): array;

    public function getSorting(): array;

    public function getValidationRules(): array;
}
