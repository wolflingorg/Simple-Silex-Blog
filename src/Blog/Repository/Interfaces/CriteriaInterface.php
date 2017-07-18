<?php

namespace Blog\Repository\Interfaces;

interface CriteriaInterface
{
    public function getEntityName(): string;

    public function getFiltering(): array;

    public function getValidationRules(): array;
}
