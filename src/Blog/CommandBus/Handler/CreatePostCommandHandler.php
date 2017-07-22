<?php

namespace Blog\CommandBus\Handler;

use Blog\CommandBus\Command\CreatePostCommand;
use Blog\Entity\Post;
use Blog\Entity\ValueObject\Uuid;
use Blog\EventBus\Event\PostWasCreatedEvent;
use Blog\Manager\Interfaces\UserManagerInterface;
use Blog\Repository\Interfaces\PostRepositoryInterface;
use SimpleBus\Message\Bus\MessageBus;

class CreatePostCommandHandler
{
    private $repo;

    private $userManager;

    private $bus;

    /**
     * @param PostRepositoryInterface $repo
     * @param UserManagerInterface $userManager
     * @param MessageBus $bus
     */
    public function __construct(PostRepositoryInterface $repo, UserManagerInterface $userManager, MessageBus $bus)
    {
        $this->repo = $repo;
        $this->userManager = $userManager;
        $this->bus = $bus;
    }

    /**
     * Processing Create Post Command
     *
     * @param CreatePostCommand $command
     */
    public function handle(CreatePostCommand $command)
    {
        $post = (new Post(new Uuid($command->id), $this->userManager->getUser()->getId()))
            ->setTitle($command->title)
            ->setBody($command->body)
            ->setIsPublished($command->isPublished);

        $this->repo->persist($post);

        $this->bus->handle(new PostWasCreatedEvent($post));
    }
}
