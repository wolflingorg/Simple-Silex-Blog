<?php

namespace Blog\Controller;

use Blog\CommandBus\Command\CreatePostCommand;
use Blog\Repository\Criteria\PostCriteria;
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

    public function searchAction(Request $request, Application $app)
    {
        $params = $request->query->all();
        $criteria = new PostCriteria($params);

        return $app['search_engine']->match($criteria);
    }

    public function showAction($uuid, Application $app)
    {
        $criteria = new PostCriteria([
            'id' => $uuid
        ]);

        return $app['search_engine']->match($criteria);
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
