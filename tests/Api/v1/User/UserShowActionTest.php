<?php
namespace Tests\Api\v1\User;

use Symfony\Component\HttpFoundation\Response;
use Tests\Api\AbstractApiTest;

class UserShowActionTest extends AbstractApiTest
{
    public function testShowNotValidUser()
    {
        $client = $this->createClient();

        $client->request('GET', '/api/v1/users/100');

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }

    public function testShowValidUser()
    {
        $client = $this->createClient();

        $client->request('GET', '/api/v1/users/1');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}