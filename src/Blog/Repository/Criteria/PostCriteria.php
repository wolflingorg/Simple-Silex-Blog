<?php

namespace Blog\Repository\Criteria;

use Blog\Entity\Post;
use Symfony\Component\Validator\Constraints as Assert;

class PostCriteria extends AbstractCriteria
{
    protected $filtering = [
        'id' => null,
        'title' => null,
        'body' => null,
        'is_published' => null,
    ];

    public function getEntityName(): string
    {
        return Post::class;
    }

    public function getValidationRules(): array
    {
        return array_merge(
            parent::getValidationRules(),
            [
                [$this->filtering['id'], new Assert\Uuid()],
                [$this->filtering['is_published'], new Assert\Choice([1, 0])],
            ]
        );
    }
}
