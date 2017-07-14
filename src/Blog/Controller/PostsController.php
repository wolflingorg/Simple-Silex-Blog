<?php

namespace Blog\Controller;

use Blog\CommandBus\Command\CreatePostCommand;
use Blog\Entity\Post;
use Blog\Repository\Manager\RepositoryManager;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class PostsController
{
    public function createAction(Request $request, Application $app)
    {
        $data = $request->getContent();

        $command = new CreatePostCommand(json_decode($data, true));
        $app['command_bus']->handle($command);

        return 'ok';
    }

    public function searchAction()
    {
        return __METHOD__;
    }

    public function showAction($uuid, Application $app)
    {
        /** @var RepositoryManager $rm */
        $rm = $app['repository_manager'];

        return $rm->get(Post::class)->findPost($uuid);
    }

    public function editAction()
    {
        return __METHOD__;
    }

    public function deleteAction()
    {
        return __METHOD__;
    }
}
