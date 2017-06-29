<?php

namespace Tests\Api;

use app;
use Silex\Application;
use Silex\WebTestCase;

abstract class AbstractApiTest extends WebTestCase
{
    /**
     * @return Application
     */
    public function createApplication()
    {
        putenv("APP_ENV=TEST");

        return app\application(true);
    }
}