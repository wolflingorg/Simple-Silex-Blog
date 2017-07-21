<?php

namespace Blog\Repository\Criteria;

use Blog\Repository\Interfaces\CriteriaInterface;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractCriteria implements CriteriaInterface
{
    const SORTING_ASC = 'ASC';

    const SORTING_DESC = 'DESC';

    /**
     * List of allowable parameters for paginating
     *
     * @var array
     */
    protected $paginating = [
        'per_page' => 10,
        'offset' => 0
    ];

    /**
     * List of allowable parameters for filtering
     *
     * @var array
     */
    protected $filtering = [];

    /**
     * List of allowable parameters for sorting
     *
     * @var array
     */
    protected $sorting = [];

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $params = $this->parsePaginationFromQueryString($params);
        $params = $this->parseFilteringFromQueryString($params);
        $this->parseOrderingFromQueryString($params);
    }

    /**
     * Applying Pagination from user query string
     *
     * @param array $params
     *
     * @return array
     */
    private function parsePaginationFromQueryString(array $params)
    {
        return array_filter($params, function ($value, $key) {
            return !(array_key_exists($key, $this->paginating) ? $this->paginating[$key] = $value : 0);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Applying Filtering from user query string
     *
     * @param array $params
     *
     * @return array
     */
    private function parseFilteringFromQueryString(array $params)
    {
        return array_filter($params, function ($value, $key) {
            return !(array_key_exists($key, $this->filtering) ? $this->filtering[$key] = $value : 0);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Applying Ordering from user query string
     *
     * @param array $params
     *
     * @return array
     */
    private function parseOrderingFromQueryString(array $params)
    {
        if (!isset($params['sort'])) {
            return $params;
        }

        foreach ((array)explode(',', $params['sort']) as $item) {
            $column = trim($item, '-');

            if (array_key_exists($column, $this->sorting)) {
                $this->sorting[$column] = '-' == $item[0] ? self::SORTING_DESC : self::SORTING_ASC;
            }
        }
        unset($params['sort']);

        return $params;
    }

    /**
     * @return array
     */
    public function getFiltering(): array
    {
        return $this->filtering;
    }

    /**
     * @return array
     */
    public function getPaginating(): array
    {
        return $this->paginating;
    }

    /**
     * @return array
     */
    public function getSorting(): array
    {
        return $this->sorting;
    }

    /**
     * Validation rules
     *
     * @return array
     */
    public function getValidationRules(): array
    {
        return [
            [$this->paginating['per_page'], new Assert\Type('numeric')],
            [$this->paginating['offset'], new Assert\Type('numeric')],
        ];
    }
}
