<?php

namespace Tests\Api;

use app;
use Blog\Entity\User;
use Blog\Entity\ValueObject\Uuid;
use Firebase\JWT\JWT;
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

    protected function getMockUser()
    {
        return new User(new Uuid('ab5763c9-1d8c-4ad7-b22e-c484c26973d3'));
    }

    protected function getMockToken()
    {
        return JWT::encode(['sub' => 'ab5763c9-1d8c-4ad7-b22e-c484c26973d3'], $this->app['parameters']['jwt_secret']);
    }
}
