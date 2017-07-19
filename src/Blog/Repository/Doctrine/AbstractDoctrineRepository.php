<?php

namespace Blog\Repository\Doctrine;

use Blog\Repository\Doctrine\Builder\AbstractBuilder;
use Blog\Repository\Doctrine\Interfaces\BuilderInterface;
use Blog\Repository\Interfaces\CriteriaInterface;
use Blog\Repository\Interfaces\RepositoryInterface;
use Doctrine\ORM\EntityRepository;

abstract class AbstractDoctrineRepository extends EntityRepository implements RepositoryInterface
{
    private $builders = [];

    public function setBuilders(array $builders)
    {
        foreach ($builders as $builder) {
            $this->addBuilder($builder);
        }
    }

    public function addBuilder(BuilderInterface $builder)
    {
        $this->builders[] = $builder;
    }

    public function persist($object)
    {
        $this->getEntityManager()->persist($object);
    }

    public function match(CriteriaInterface $criteria)
    {
        $entityName = $criteria->getEntityName();
        $alias = AbstractBuilder::getTableAliasByCriteria($criteria);

        $queryBuilder = $this->getEntityManager()->getRepository($entityName)->createQueryBuilder($alias);

        foreach ($this->builders as $builder) {
            if (true === $builder->supports($criteria)) {
                $builder->build($criteria, $queryBuilder);
            }
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
