<?php

namespace app;

use Blog\Exception\ValidationException;
use Doctrine\DBAL\DBALException;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

function exceptions(Application $app)
{
    // processing exceptions
    $app->error(function (ValidationException $e, Request $request, $code) use ($app) {
        return $app['output_builder']
            ->setResponseCode($code)
            ->getResponse($request, [
                'message' => 'Validation Failed',
                'errors' => (array)$e->getMessages()
            ]);
    });

    // prod environment
    if ($app['environment'] == 'PROD') {
        $app->error(function (DBALException $e, Request $request, $code) use ($app) {
            if (preg_match('/SQLSTATE\[(\d+)\]/', $e->getMessage(), $matches)) {
                $sqlstate = $matches[0];
            } else {
                $sqlstate = 'SQLSTATE[UNKNOWN]';
            }

            return $app['output_builder']
                ->setResponseCode($code)
                ->getResponse($request, [
                    'message' => 'SQL Failed',
                    'errors' => (array)$sqlstate
                ]);
        });

        $app->error(function (\Exception $e, Request $request, $code) use ($app) {
            return $app['output_builder']
                ->setResponseCode($code)
                ->getResponse($request, [
                    'message' => 'Exception',
                    'errors' => 'Something went wrong'
                ]);
        });
    }
}
