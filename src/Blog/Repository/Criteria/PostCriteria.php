<?php

namespace Blog\Repository\Criteria;

use Blog\Entity\Post;
use Blog\Repository\Interfaces\CriteriaInterface;

class PostCriteria implements CriteriaInterface
{
    public function getEntityName(): string
    {
        return Post::class;
    }
}
