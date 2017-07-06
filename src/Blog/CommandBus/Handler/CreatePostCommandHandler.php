<?php

namespace Blog\CommandBus\Handler;

use Blog\CommandBus\Command\CreatePostCommand;
use Blog\Entity\Post;
use Blog\Entity\User;
use Blog\EventBus\Event\PostWasCreatedEvent;
use Blog\Repository\PostRepository;
use SimpleBus\Message\Bus\MessageBus;

class CreatePostCommandHandler
{
    private $repository;
    private $currentUser;
    private $bus;

    public function __construct(PostRepository $repository, User $currentUser, MessageBus $bus)
    {
        $this->repository = $repository;
        $this->currentUser = $currentUser;
        $this->bus = $bus;
    }

    public function handle(CreatePostCommand $command)
    {
        $post = (new Post($command->id, $this->currentUser))
            ->setTitle($command->title)
            ->setBody($command->body)
            ->setIsPublished($command->isPublished);

        $this->repository->createPost($post);

        $this->bus->handle(new PostWasCreatedEvent($post));
    }
}
