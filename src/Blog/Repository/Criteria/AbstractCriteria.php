<?php

namespace Blog\Repository\Criteria;

use Blog\Repository\Interfaces\CriteriaInterface;
use Symfony\Component\Validator\Constraints as Assert;

abstract class AbstractCriteria implements CriteriaInterface
{
    const SORTING_ASC = 'ASC';

    const SORTING_DESC = 'DESC';

    protected $paginating = [
        'per_page' => 10,
        'offset' => 0
    ];

    protected $filtering = [];

    protected $sorting = [];

    public function __construct(array $params)
    {
        $params = $this->parsePaginationFromQueryString($params);
        $params = $this->parseFilteringFromQueryString($params);
        $this->parseOrderingFromQueryString($params);
    }

    private function parsePaginationFromQueryString(array $params)
    {
        return array_filter($params, function ($value, $key) {
            return !(array_key_exists($key, $this->paginating) ? $this->paginating[$key] = $value : 0);
        }, ARRAY_FILTER_USE_BOTH);
    }

    private function parseFilteringFromQueryString(array $params)
    {
        return array_filter($params, function ($value, $key) {
            return !(array_key_exists($key, $this->filtering) ? $this->filtering[$key] = $value : 0);
        }, ARRAY_FILTER_USE_BOTH);
    }

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

    public function getFiltering(): array
    {
        return $this->filtering;
    }

    public function getPaginating(): array
    {
        return $this->paginating;
    }

    public function getSorting(): array
    {
        return $this->sorting;
    }

    public function getValidationRules(): array
    {
        return [
            [$this->paginating['per_page'], new Assert\Type('numeric')],
            [$this->paginating['offset'], new Assert\Type('numeric')],
        ];
    }
}
