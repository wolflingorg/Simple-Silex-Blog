<?php

namespace Blog\Repository\Manager;

use Blog\Repository\Interfaces\RepositoryInterface;

class RepositoryManager
{
    /**
     * @var RepositoryInterface[]
     */
    protected $repositories = [];

    public function __construct(array $repositories = [])
    {
        foreach ($repositories as $key => $repository) {
            if ($repository instanceof RepositoryInterface) {
                $this->add($key, $repository);
            }
        }
    }

    public function add($key, RepositoryInterface $repository)
    {
        $this->repositories[$key] = $repository;

        return $this;
    }

    public function get($key): RepositoryInterface
    {
        if (!$this->has($key)) {
            throw new \InvalidArgumentException(sprintf('Repository %s not found', $key));
        }

        return $this->repositories[$key];
    }

    public function has($key): bool
    {
        return isset($this->repositories[$key]);
    }
}
