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

        $this->assertEquals(5, sizeof($posts->result));
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

        $this->assertEquals(10, sizeof($posts->result));
    }

    public function testShowValidPostsByTitle()
    {
        $client = $this->createClient();

        $client->request('GET', "/api/v1/posts/?title=Australia", [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $posts = json_decode($client->getResponse()->getContent());

        $this->assertEquals(1, sizeof($posts->result));
    }

    public function testShowValidPostsByBody()
    {
        $client = $this->createClient();

        $client->request('GET', "/api/v1/posts/?body=Chelsea%20Manning", [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $posts = json_decode($client->getResponse()->getContent());

        $this->assertEquals(1, sizeof($posts->result));
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

        $this->assertEquals(1, sizeof($posts->result));
    }

    public function testShowValidPostsByUserWithSortingByIdASC()
    {
        $client = $this->createClient();

        $user = 'ab5763c9-1d8c-4ad7-b22e-c484c26973d3';

        $client->request('GET', "/api/v1/posts/?user={$user}&sort=id", [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $posts = json_decode($client->getResponse()->getContent());

        $expectingUuid = '0117ed73-547f-48c0-a304-fb202a69d0ca';

        $this->assertEquals($expectingUuid, $posts->result[0]->id->uuid);
    }

    public function testShowValidPostsByUserWithSortingByIdDESC()
    {
        $client = $this->createClient();

        $user = 'ab5763c9-1d8c-4ad7-b22e-c484c26973d3';

        $client->request('GET', "/api/v1/posts/?user={$user}&sort=-id", [], [], [
            'HTTP_ACCEPT' => 'application/json',
        ]);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $posts = json_decode($client->getResponse()->getContent());

        $expectingUuid = 'f2bfd7da-f366-4fcb-82d3-75d3e27bf063';

        $this->assertEquals($expectingUuid, $posts->result[0]->id->uuid);
    }
}
