<?php

namespace Tests\Api\v1\User;

use Symfony\Component\HttpFoundation\Response;
use Tests\Api\AbstractApiTest;

class PostShowActionTest extends AbstractApiTest
{
    public function testShowValidPost()
    {
        $client = $this->createClient();

        $uuid = '445ace43-3fd5-4d9f-ad03-5b80bc36e9c0';

        $client->request('GET', "/api/v1/posts/${uuid}/", [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        echo $client->getResponse()->getContent();

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }
}
