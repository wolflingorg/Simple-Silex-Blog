<?php

namespace app;

use Blog\CommandBus\Middleware\CommandValidationException;
use Doctrine\DBAL\DBALException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

function exceptions(Application $app)
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

    $app->error(function (DBALException $e, Request $request, $code) use ($app) {
        if (preg_match('/SQLSTATE\[(\d+)\]/', $e->getMessage(), $matches)) {
            $sqlstate = $matches[0];
        } else {
            $sqlstate = 'SQLSTATE[UNKNOWN]';
        }

        return $app['output_builder']
            ->setContent([
                'message' => 'SQL Failed',
                'errors' => (array)$sqlstate
            ])
            ->setStatusCode($code)->
            getResponse();
    });

    $app->error(function (\Exception $e, Request $request, $code) use ($app) {
        return $app['output_builder']
            ->setContent([
                'message' => 'Exception',
                'errors' => 'Something went wrong'
            ])
            ->setStatusCode($code)->
            getResponse();
    });
}
