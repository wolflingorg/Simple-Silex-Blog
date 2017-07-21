<?php

namespace Blog\Service\SearchEngine\Interfaces;

use Blog\Repository\Interfaces\CriteriaInterface;

/**
 * Provides an interface to getting results of search
 *
 * @package Blog\Service\SearchEngine\Interfaces
 */
interface SearchEngineInterface
{
    /**
     * Matches search creteria and returns search result
     *
     * @param CriteriaInterface $criteria
     *
     * @return SearchResultInterface
     */
    public function match(CriteriaInterface $criteria): SearchResultInterface;

    /**
     * Append middleware that will be run before the search
     * Callable function will get one parameter object instance of the CriteriaInterface
     *
     * @param callable $callable
     * @param int $priority
     *
     * @return mixed
     */
    public function before(callable $callable, $priority = 0);

    /**
     * Append middleware that will be run after the search
     * Callable function will get two parameters:
     * - object instance of the CriteriaInterface
     * - object instance of the SearchResultInterface
     *
     * @param callable $callable
     * @param int $priority
     *
     * @return mixed
     */
    public function after(callable $callable, $priority = 0);
}
