<?php

namespace Tests\Api\v1\User;

use Symfony\Component\HttpFoundation\Response;
use Tests\Api\AbstractApiTest;

class PostSearchActionTest extends AbstractApiTest
{
    public function testShowValidPost()
    {
        $client = $this->createClient();

        $client->request('GET', "/api/v1/posts/?is_published=1", [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        echo $client->getResponse()->getContent();

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
