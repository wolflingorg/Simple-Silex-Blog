<?php

namespace Tests\Api\v1\User;

use Symfony\Component\HttpFoundation\Response;
use Tests\Api\AbstractApiTest;

class PostCreateActionTest extends AbstractApiTest
{
    public function testCreateNewPost()
    {
        $client = $this->createClient();

        $data = json_encode([
            'id' => '335ace43-3fd5-4d9f-ad03-5b80bc36e9c0',
            'title' => 'Some title',
            'body' => 'Some body'
        ]);

        $client->request('POST', '/api/v1/posts/', [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ], $data);

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());

        $this->assertEquals('"ok"', $client->getResponse()->getContent());
    }

    public function testCreateNewPostWithoutId()
    {
        $client = $this->createClient();

        $data = json_encode([
            'title' => 'Some title',
            'body' => 'Some body',
        ]);

        $client->request('POST', '/api/v1/posts/', [], [], [], $data);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testCreateNewPostWithWrongId()
    {
        $client = $this->createClient();

        $data = json_encode([
            'id' => '4d9f-ad03-5b80bc36e9c0',
            'title' => 'Some title',
            'body' => 'Some body'
        ]);

        $client->request('POST', '/api/v1/posts/', [], [], [], $data);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }
}
