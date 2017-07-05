<?php

namespace app;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

function middlewares(Application $app)
{
    // processing exceptions
    $app->error(function (\Exception $e, Request $request, $code) use ($app) {
        return $app['output_builder']
            ->setContent([
                'error' => [
                    'message' => $e->getMessage(),
                    'content' => 'exception'
                ]
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
