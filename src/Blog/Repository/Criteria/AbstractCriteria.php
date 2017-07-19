<?php

namespace Blog\Repository\Criteria;

use Blog\Repository\Interfaces\CriteriaInterface;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractCriteria implements CriteriaInterface
{
    protected $filtering = [];

    protected $paginating = [
        'per_page' => 10,
        'offset' => 0
    ];

    public function __construct(array $params)
    {
        foreach ($params as $key => $value) {
            if (array_key_exists($key, $this->paginating)) {
                $this->paginating[$key] = $value;
            } elseif (array_key_exists($key, $this->filtering)) {
                $this->filtering[$key] = $value;
            }
        }
    }

    public function getFiltering(): array
    {
        return $this->filtering;
    }

    public function getPaginating(): array
    {
        return $this->paginating;
    }

    public function getValidationRules(): array
    {
        return [
            [$this->paginating['per_page'], new Assert\Type('numeric')],
            [$this->paginating['offset'], new Assert\Type('numeric')],
        ];
    }
}
