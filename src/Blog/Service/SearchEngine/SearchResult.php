<?php

namespace Blog\Service\SearchEngine;

use Blog\Service\SearchEngine\Interfaces\SearchResultInterface;

class SearchResult implements SearchResultInterface
{
    private $result;

    private $rowCount = 0;

    private $perPage = 0;

    private $offset = 0;

    /**
     * @param $result
     */
    public function __construct($result)
    {
        $this->result = $result;
    }

    /**
     * @inheritdoc
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @inheritdoc
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    /**
     * @inheritdoc
     */
    public function setRowCount(int $rowCount)
    {
        $this->rowCount = $rowCount;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPerPage(): int
    {
        return $this->perPage;
    }

    /**
     * @inheritdoc
     */
    public function setPerPage(int $perPage)
    {
        $this->perPage = $perPage;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @inheritdoc
     */
    public function setOffset(int $offset)
    {
        $this->offset = $offset;

        return $this;
    }
}
