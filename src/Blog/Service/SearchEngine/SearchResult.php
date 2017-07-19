<?php

namespace Blog\Service\SearchEngine;

use Blog\Service\SearchEngine\Interfaces\SearchResultInterface;

class SearchResult implements SearchResultInterface
{
    private $result;

    private $rowCount = 0;

    private $perPage = 0;

    private $offset = 0;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function setRowCount(int $rowCount)
    {
        $this->rowCount = $rowCount;

        return $this;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }

    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function setOffset(int $offset)
    {
        $this->offset = $offset;

        return $this;
    }
}
