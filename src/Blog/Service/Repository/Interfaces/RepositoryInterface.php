<?php

namespace Blog\Service\Repository\Interfaces;

interface RepositoryInterface
{
    public function findByPk($id);

    public function findByCriteria($criteria);

    public function insert($data);

    public function save($id, $data);

    public function delete($id);
}
