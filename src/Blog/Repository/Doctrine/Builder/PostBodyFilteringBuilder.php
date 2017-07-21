<?php

namespace Blog\Repository\Doctrine\Builder;

use Blog\Entity\Post;
use Blog\Repository\Doctrine\Interfaces\BuilderInterface;
use Blog\Repository\Interfaces\CriteriaInterface;
use Doctrine\ORM\QueryBuilder;

class PostBodyFilteringBuilder implements BuilderInterface
{
    /**
     * @inheritdoc
     */
    public function supports(CriteriaInterface $criteria): bool
    {
        return Post::class == $criteria->getEntityName() && isset($criteria->getFiltering()['body']);
    }

    /**
     * @inheritdoc
     */
    public function build(CriteriaInterface $criteria, QueryBuilder $queryBuilder)
    {
        $alias = $queryBuilder->getRootAliases()[0];

        $queryBuilder->andWhere("{$alias}.body LIKE :body");
        $queryBuilder->setParameter(':body', '%' . $criteria->getFiltering()['body'] . '%');
    }
}
