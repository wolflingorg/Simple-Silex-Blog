<?php

namespace Tests\Api\v1\User;

use Symfony\Component\HttpFoundation\Response;
use Tests\Api\AbstractApiTest;

class PostSearchActionTest extends AbstractApiTest
{
    public function testShowValidPublishedPosts()
    {
        $client = $this->createClient();

        $client->request('GET', "/api/v1/posts/?is_published=1", [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $posts = json_decode($client->getResponse()->getContent());
        $this->assertEquals(5, sizeof($posts));
    }

    public function testShowValidPostsByUser()
    {
        $client = $this->createClient();

        $user = 'ab5763c9-1d8c-4ad7-b22e-c484c26973d3';

        $client->request('GET', "/api/v1/posts/?user={$user}", [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $posts = json_decode($client->getResponse()->getContent());
        $this->assertEquals(10, sizeof($posts));
    }

    public function testShowValidPostsByTitle()
    {
        $client = $this->createClient();

        $client->request('GET', "/api/v1/posts/?title=Australia", [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $posts = json_decode($client->getResponse()->getContent());
        $this->assertEquals(1, sizeof($posts));
    }

    public function testShowValidPostsByBody()
    {
        $client = $this->createClient();

        $client->request('GET', "/api/v1/posts/?body=Chelsea%20Manning", [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $posts = json_decode($client->getResponse()->getContent());
        $this->assertEquals(1, sizeof($posts));
    }

    public function testShowValidPostsByUserWithPagination()
    {
        $client = $this->createClient();

        $user = 'ab5763c9-1d8c-4ad7-b22e-c484c26973d3';

        $client->request('GET', "/api/v1/posts/?user={$user}&per_page=3&offset=9", [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $posts = json_decode($client->getResponse()->getContent());
        $this->assertEquals(1, sizeof($posts));
    }
}
