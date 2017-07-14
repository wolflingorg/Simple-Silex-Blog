<?php

namespace Blog\Service\SearchEngine\DBAL;

use Blog\Service\Repository\RepositoryManager;
use Blog\Service\SearchEngine\DBAL\Interfaces\BuilderInterface;
use Blog\Service\SearchEngine\Interfaces\CriteriaInterface;
use Blog\Service\SearchEngine\Interfaces\ResultInterface;
use Blog\Service\SearchEngine\Interfaces\SearchEngineInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;

class SearchEngine implements SearchEngineInterface
{
    private $rm;
    private $conn;
    private $builders = [];

    public function __construct(Connection $conn, RepositoryManager $rm)
    {
        $this->rm = $rm;
        $this->conn = $conn;
    }

    public function add(BuilderInterface $builder)
    {
        $this->builders[] = $builder;
    }

    public function match(CriteriaInterface $criteria): ResultInterface
    {
        $queryBuilder = new QueryBuilder($this->conn);
        foreach ($this->builders as $builder) {
            if (true === $builder->supports($criteria)) {
                $builder->build($criteria, $queryBuilder);
            }
        }

        return new Result($queryBuilder);
    }
}
