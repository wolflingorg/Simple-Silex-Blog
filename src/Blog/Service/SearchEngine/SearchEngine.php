<?php

namespace Blog\Service\SearchEngine;

use Blog\Repository\Interfaces\CriteriaInterface;
use Blog\Repository\Interfaces\RepositoryInterface;
use Blog\Service\SearchEngine\Interfaces\SearchEngineInterface;
use Blog\Service\SearchEngine\Interfaces\SearchResultInterface;

class SearchEngine implements SearchEngineInterface
{
    /**
     * @var RepositoryInterface[]
     */
    protected $repositoryMap = [];

    private $middlewares = [
        'before' => [],
        'after' => []
    ];

    public function setRepositoryMap(array $map)
    {
        foreach ($map as $entityName => $repository) {
            $this->addRepository($entityName, $repository);
        }
    }

    public function addRepository($entityName, RepositoryInterface $repository)
    {
        $this->repositoryMap[$entityName] = $repository;
    }

    public function match(CriteriaInterface $criteria): SearchResultInterface
    {
        // before middlewares
        foreach ($this->middlewares['before'] as $middleware) {
            call_user_func($middleware, $criteria);
        }

        $entityName = $criteria->getEntityName();
        if (!isset($this->repositoryMap[$entityName])) {
            throw new \InvalidArgumentException(sprintf('Couldn\'t find repository %s', $entityName));
        }
        $repo = $this->repositoryMap[$entityName];

        $result = new SearchResult($repo->match($criteria));
        $result
            ->setRowCount($repo->getRowCount())
            ->setPerPage($criteria->getPaginating()['per_page'])
            ->setOffset($criteria->getPaginating()['offset']);

        // after middlewares
        foreach ($this->middlewares['after'] as $middleware) {
            call_user_func($middleware, $criteria, $result);
        }

        return $result;
    }

    public function before(callable $callable, $priority = 0)
    {
        $this->addMiddleware('before', $callable, $priority);
    }

    private function addMiddleware($type, callable $callable, $priority = 0)
    {
        if ($priority == 0) {
            $this->middlewares[$type][] = $callable;
        } else {
            $this->middlewares[$type][$priority] = $callable;
        }
    }

    public function after(callable $callable, $priority = 0)
    {
        $this->addMiddleware('after', $callable, $priority);
    }
}
