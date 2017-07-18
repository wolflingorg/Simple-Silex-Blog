<?php

namespace Blog\Repository\Doctrine\Builder;

use Blog\Repository\Doctrine\Interfaces\BuilderInterface;
use Blog\Repository\Interfaces\CriteriaInterface;

abstract class AbstractBuilder implements BuilderInterface
{
    static public function getTableAliasByCriteria(CriteriaInterface $criteria)
    {
        $entityName = $criteria->getEntityName();

        return mb_strtolower(substr(strrchr($entityName, "\\"), 1, 1));
    }
}
