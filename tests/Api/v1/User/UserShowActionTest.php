<?php
namespace Tests\Api\v1\User;

use Symfony\Component\HttpFoundation\Response;
use Tests\Api\AbstractApiTest;

class UserShowActionTest extends AbstractApiTest
{
    public function testOkResponse()
    {
        $client = $this->createClient();

        $client->request('GET', '/api/v1/users/');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}