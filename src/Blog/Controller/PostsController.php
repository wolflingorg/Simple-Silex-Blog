<?php

namespace Blog\Controller;

use Blog\CommandBus\Command\CreatePostCommand;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostsController
{
    public function createAction(Request $request, Application $app)
    {
        $data = $request->getContent();

        $command = new CreatePostCommand(json_decode($data, true));
        $app['command_bus']->handle($command);

        return new Response(sprintf("Hello from %s", __METHOD__));
    }

    public function searchAction(Application $app)
    {
        return new Response(sprintf("Hello from %s", __METHOD__));
    }

    public function showAction(Application $app)
    {
        return new Response(sprintf("Hello from %s", __METHOD__));
    }

    public function editAction(Application $app)
    {
        return new Response(sprintf("Hello from %s", __METHOD__));
    }

    public function deleteAction(Application $app)
    {
        return new Response(sprintf("Hello from %s", __METHOD__));
    }
}
