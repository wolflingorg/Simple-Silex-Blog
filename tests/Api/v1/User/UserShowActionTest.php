<?php
namespace Tests\Api\v1\User;

use Tests\Api\v1\AbstractApiV1Test;

class UserShowActionTest extends AbstractApiV1Test
{
    public function testShowNotValidUser()
    {
        $client = $this->createClient();

        $response = $client->get('users/100');

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testShowValidUser()
    {
        $client = $this->createClient();

        $response = $client->get('users/1');

        $this->assertEquals(200, $response->getStatusCode());
    }
}