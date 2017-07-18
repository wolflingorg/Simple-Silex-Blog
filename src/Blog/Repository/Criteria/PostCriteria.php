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
        'user' => null,
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
                [$this->filtering['user'], new Assert\Uuid()],
                [$this->filtering['is_published'], new Assert\Choice([1, 0])],
                [$this->filtering['body'], new Assert\Type('string')],
                [$this->filtering['title'], new Assert\Type('string')],
            ]
        );
    }
}
