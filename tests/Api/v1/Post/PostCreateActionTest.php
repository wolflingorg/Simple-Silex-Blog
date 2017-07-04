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
            'body' => 'Some body',
            'user' => 'ab5763c9-1d8c-4ad7-b22e-c484c26973d3'
        ]);

        $client->request('POST', '/api/v1/posts/', [], [], [], $data);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testCreateNewPostWithoutId()
    {
        $client = $this->createClient();

        $data = json_encode([
            'title' => 'Some title',
            'body' => 'Some body',
            'user' => 'ab5763c9-1d8c-4ad7-b22e-c484c26973d3'
        ]);

        $client->request('POST', '/api/v1/posts/', [], [], [], $data);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testCreateNewPostWithoutUser()
    {
        $client = $this->createClient();

        $data = json_encode([
            'id' => '335ace43-3fd5-4d9f-ad03-5b80bc36e9c0',
            'title' => 'Some title',
            'body' => 'Some body'
        ]);

        $client->request('POST', '/api/v1/posts/', [], [], [], $data);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testCreateNewPostWithWrongUuid()
    {
        $client = $this->createClient();

        $data = json_encode([
            'id' => '4d9f-ad03-5b80bc36e9c0',
            'title' => 'Some title',
            'body' => 'Some body',
            'user' => '4ad7-b22e-c484c26973d3'
        ]);

        $client->request('POST', '/api/v1/posts/', [], [], [], $data);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }
}
