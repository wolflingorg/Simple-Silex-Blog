<?php

namespace Blog\Service\SearchEngine;

use Blog\Service\SearchEngine\Interfaces\ResultInterface;

class SearchResult implements ResultInterface
{
    public function __construct($result)
    {
        print_r($result);
    }
}
