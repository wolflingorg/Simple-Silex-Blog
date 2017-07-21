<?php

namespace Blog\Service\SearchEngine\Interfaces;

interface SearchResultInterface
{
    /**
     * Returns raw result from the repository
     *
     * @return mixed
     */
    public function getResult();

    /**
     * Returns how many rows there would be in the result set, disregarding any LIMIT clause
     *
     * @return int
     */
    public function getRowCount(): int;

    /**
     * @param int $rowCount
     *
     * @return mixed
     */
    public function setRowCount(int $rowCount);

    /**
     * Returns how many items will be returned per each request
     *
     * @return int
     */
    public function getPerPage(): int;

    /**
     * @param int $perPage
     *
     * @return mixed
     */
    public function setPerPage(int $perPage);

    /**
     * Offset from the beginning
     *
     * @return int
     */
    public function getOffset(): int;

    /**
     * @param int $offset
     *
     * @return mixed
     */
    public function setOffset(int $offset);
}
