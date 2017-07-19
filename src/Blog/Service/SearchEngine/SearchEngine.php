<?php

namespace Blog\Service\SearchEngine;

use Blog\Repository\Interfaces\CriteriaInterface;
use Blog\Repository\Interfaces\CriteriaValidatorInterface;
use Blog\Repository\Interfaces\RepositoryInterface;
use Blog\Service\SearchEngine\Interfaces\SearchEngineInterface;
use Blog\Service\SearchEngine\Interfaces\SearchResultInterface;

class SearchEngine implements SearchEngineInterface
{
    /**
     * @var RepositoryInterface[]
     */
    protected $repositoryMap = [];
    private $criteriaValidator;

    public function __construct(CriteriaValidatorInterface $criteriaValidator)
    {
        $this->criteriaValidator = $criteriaValidator;
    }

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
        $this->criteriaValidator->validate($criteria);

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

        return $result;
    }
}
