<?php
namespace Tests\Api;

use Silex\Application;
use Silex\WebTestCase;
use app;

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