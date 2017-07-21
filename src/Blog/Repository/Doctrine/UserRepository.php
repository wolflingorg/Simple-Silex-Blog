<?php

namespace Blog\Repository\Doctrine;

use Silex\Application;

class UserRepository
{
    private $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
}
