<?php

namespace Blog\QueryPipeline\PostCriteria;

use Blog\Entity\Post;
use Blog\Service\SearchEngine\Interfaces\CriteriaInterface;
use Blog\Service\SearchEngine\Interfaces\FilteringInterface;
use Blog\Service\SearchEngine\Interfaces\OrderingInterface;
use Blog\Service\SearchEngine\Interfaces\PaginatingInterface;

class PostCriteria implements CriteriaInterface
{
    private $filtering;

    public function __construct(array $query)
    {
        $this->filtering = new PostFiltering($query);
    }

    public function getResourceName()
    {
        return Post::class;
    }

    public function getFiltering(): FilteringInterface
    {
        return $this->filtering;
    }

    public function getOrdering(): OrderingInterface
    {
        // TODO: Implement getOrdering() method.
    }

    public function getPaginating(): PaginatingInterface
    {
        // TODO: Implement getPaginating() method.
    }
}
