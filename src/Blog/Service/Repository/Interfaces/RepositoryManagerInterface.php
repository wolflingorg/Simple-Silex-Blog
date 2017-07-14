<?php

namespace Blog\Service\Repository\Interfaces;

interface RepositoryManagerInterface
{
    public function add($key, RepositoryInterface $repository);

    public function get($key): RepositoryInterface;

    public function has($key): bool;
}
