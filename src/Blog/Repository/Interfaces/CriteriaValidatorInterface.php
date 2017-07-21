<?php

namespace Blog\Repository\Interfaces;

interface CriteriaValidatorInterface
{
    /**
     * Validates Search Criteria
     *
     * @param CriteriaInterface $criteria
     */
    public function validate(CriteriaInterface $criteria);
}
