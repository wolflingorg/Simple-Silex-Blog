<?php

namespace Blog\Repository\Doctrine;

use Silex\Application;

class PhotoRepository
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }
}
