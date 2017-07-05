<?php

namespace app;

use Blog\CommandBus\Middleware\CommandValidationException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

function middlewares(Application $app)
{
    // processing exceptions
    $app->error(function (CommandValidationException $e, Request $request, $code) use ($app) {
        return $app['output_builder']
            ->setContent([
                'message' => 'Validation Failed',
                'errors' => (array)$e->getMessages()
            ])
            ->setStatusCode($code)->
            getResponse();
    });

    $app->error(function (\Exception $e, Request $request, $code) use ($app) {
        return $app['output_builder']
            ->setContent([
                'message' => 'Exception',
                'errors' => (array)$e->getMessage()
            ])
            ->setStatusCode($code)->
            getResponse();
    });

    // processing response
    $app->after(function (Request $request, Response $response) use ($app) {
        return $app['output_builder']
            ->setContent($response->getContent())
            ->setStatusCode($response->getStatusCode())
            ->getResponse();
    });
}
