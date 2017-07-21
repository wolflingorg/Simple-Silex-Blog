<?php

namespace Blog\Repository\Interfaces;

interface RepositoryInterface
{
    /**
     * Save object to repository
     *
     * @param $object
     */
    public function persist($object);

    /**
     * Find entities using given search criteria
     *
     * @param CriteriaInterface $criteria
     *
     * @return mixed
     */
    public function match(CriteriaInterface $criteria);

    /**
     * Returns number of affected rows during last query
     *
     * @return mixed
     */
    public function getRowCount();
}
