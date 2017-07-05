<?php

namespace Blog\CommandBus\Handler;

use Blog\CommandBus\Command\CreatePostCommand;
use Blog\Entity\Post;
use Blog\Entity\User;
use Blog\Repository\PostRepository;

class CreatePostCommandHandler
{
    private $repository;
    private $currentUser;

    public function __construct(PostRepository $repository, User $currentUser)
    {
        $this->repository = $repository;
        $this->currentUser = $currentUser;
    }

    public function handle(CreatePostCommand $command)
    {
        $post = (new Post($command->id, $this->currentUser))
            ->setTitle($command->title)
            ->setBody($command->body)
            ->setIsPublished($command->isPublished);

        $this->repository->createPost($post);
    }
}
