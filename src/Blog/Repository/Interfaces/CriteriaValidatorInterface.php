<?php

namespace Blog\Repository\Interfaces;

interface CriteriaValidatorInterface
{
    public function validate(CriteriaInterface $criteria);
}
