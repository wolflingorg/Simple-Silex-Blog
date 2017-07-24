<?php

namespace Blog\Repository\Criteria;

use Blog\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserCriteria extends AbstractCriteria
{
    protected $filtering = [
        'id' => null,
    ];

    protected $sorting = [
        'id' => null,
    ];

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return User::class;
    }

    /**
     * Validation rules
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return array_merge(
            parent::getValidationRules(),
            [
                [$this->filtering['id'], new Assert\Uuid()],
            ]
        );
    }
}
