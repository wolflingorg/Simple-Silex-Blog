<?php

namespace Tests\Api\v1\User;

use Symfony\Component\HttpFoundation\Response;
use Tests\Api\AbstractApiTest;

class PostShowActionTest extends AbstractApiTest
{
    public function testShowValidPost()
    {
        $client = $this->createClient();

        $uuid = '22fd005c-7cd4-45b9-a11c-d6537753e7b6';

        $client->request('GET', "/api/v1/posts/${uuid}/", [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $posts = json_decode($client->getResponse()->getContent());
        $this->assertEquals(1, sizeof($posts));
    }
}
