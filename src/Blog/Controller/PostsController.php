<?php

namespace Blog\Controller;

use Blog\CommandBus\Command\CreatePostCommand;
use Blog\Entity\Post;
use Blog\QueryPipeline\PostCriteria\PostCriteria;
use Blog\Repository\Doctrine\PostRepository;
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
        $criteria = new PostCriteria($request->query->all());
        $result = $app['dbal_search_engine']->match($criteria);

        return __METHOD__;
    }

    public function showAction($uuid, Application $app)
    {
        /** @var PostRepository $repo */
        $repo = $app['dbal_repository_manager']->get(Post::class);

        return $repo->findPost($uuid);
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
