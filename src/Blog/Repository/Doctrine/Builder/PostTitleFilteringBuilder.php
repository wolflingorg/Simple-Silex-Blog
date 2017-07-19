<?php

namespace Blog\Repository\Doctrine\Builder;

use Blog\Entity\Post;
use Blog\Repository\Doctrine\Interfaces\BuilderInterface;
use Blog\Repository\Interfaces\CriteriaInterface;
use Doctrine\ORM\QueryBuilder;

class PostTitleFilteringBuilder implements BuilderInterface
{

    public function supports(CriteriaInterface $criteria): bool
    {
        return Post::class == $criteria->getEntityName() && isset($criteria->getFiltering()['title']);
    }

    public function build(CriteriaInterface $criteria, QueryBuilder $queryBuilder)
    {
        $alias = $queryBuilder->getRootAliases()[0];

        $queryBuilder->andWhere("{$alias}.title LIKE :title");
        $queryBuilder->setParameter(':title', '%' . $criteria->getFiltering()['title'] . '%');
    }
}
