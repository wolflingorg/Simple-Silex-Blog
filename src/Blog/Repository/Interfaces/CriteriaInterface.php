<?php

namespace Blog\Repository\Interfaces;

interface CriteriaInterface
{
    /**
     * @return string
     */
    public function getEntityName(): string;

    /**
     * @return array
     */
    public function getFiltering(): array;

    /**
     * @return array
     */
    public function getPaginating(): array;

    /**
     * @return array
     */
    public function getSorting(): array;

    /**
     * @return array
     */
    public function getValidationRules(): array;
}
