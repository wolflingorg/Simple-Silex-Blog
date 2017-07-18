<?php

namespace Blog\Repository\Doctrine\Builder;

use Blog\Entity\Post;
use Blog\Repository\Interfaces\CriteriaInterface;
use Doctrine\ORM\QueryBuilder;

class PostBodyFilteringBuilder extends AbstractBuilder
{

    public function supports(CriteriaInterface $criteria): bool
    {
        return Post::class == $criteria->getEntityName() && isset($criteria->getFiltering()['body']);
    }

    public function build(CriteriaInterface $criteria, QueryBuilder $queryBuilder)
    {
        $alias = AbstractBuilder::getTableAliasByCriteria($criteria);

        $queryBuilder->andWhere("{$alias}.body LIKE :body");
        $queryBuilder->setParameter(':body', '%' . $criteria->getFiltering()['body'] . '%');
    }
}
