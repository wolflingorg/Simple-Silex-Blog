<?php

namespace Blog\CommandBus\Handler;

use Blog\CommandBus\Command\CreatePostCommand;
use Blog\Entity\Post;
use Blog\Entity\User;
use Blog\Entity\ValueObject\Uuid;
use Blog\EventBus\Event\PostWasCreatedEvent;
use Blog\Repository\Interfaces\PostRepositoryInterface;
use SimpleBus\Message\Bus\MessageBus;

class CreatePostCommandHandler
{
    private $repo;

    private $currentUser;

    private $bus;

    public function __construct(PostRepositoryInterface $repo, User $currentUser, MessageBus $bus)
    {
        $this->repo = $repo;
        $this->currentUser = $currentUser;
        $this->bus = $bus;
    }

    public function handle(CreatePostCommand $command)
    {
        $post = (new Post(new Uuid($command->id), new Uuid((string)$this->currentUser)))
            ->setTitle($command->title)
            ->setBody($command->body)
            ->setIsPublished($command->isPublished);

        $this->repo->persist($post);

        $this->bus->handle(new PostWasCreatedEvent($post));
    }
}
