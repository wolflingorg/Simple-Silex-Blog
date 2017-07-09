<?php

namespace Tests\Api;

use app;
use Silex\Application;
use Silex\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

abstract class AbstractApiTest extends WebTestCase
{
    /**
     * @return Application
     */
    public function createApplication()
    {
        putenv("APP_ENV=TEST");

        return app\application();
    }

    public function setUp()
    {
        parent::setUp();

        // loading fixtures
        /** @var \Symfony\Component\Console\Application $application */
        $application = $this->app['console'];
        $application->setAutoExit(false);
        $input = new ArrayInput(array(
            'command' => 'fixtures:load'
        ));
        $input->setInteractive(false);
        $application->run($input, new NullOutput());
    }
}
