<?php

namespace Blog\Repository\DBAL;

use Silex\Application;

class PhotoRepository
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
}
