<?php

namespace Tests\Api\v1\User;

use Symfony\Component\HttpFoundation\Response;
use Tests\Api\AbstractApiTest;

class PostSearchActionTest extends AbstractApiTest
{
    public function testShowValidPost()
    {
        $client = $this->createClient();

        $user = 'ab5763c9-1d8c-4ad7-b22e-c484c26973d3';

        $client->request('GET', "/api/v1/posts/?isPublished=1&user={$user}", [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        echo $client->getResponse()->getContent();

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
