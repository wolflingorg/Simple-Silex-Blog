<?php

namespace Blog\CommandBus\Handler;

use Blog\CommandBus\Command\CreatePostCommand;
use Blog\Entity\Post;
use Blog\Entity\User;
use Blog\Entity\ValueObject\Uuid;
use Blog\EventBus\Event\PostWasCreatedEvent;
use Blog\Repository\Manager\RepositoryManager;
use Blog\Repository\PostRepository;
use SimpleBus\Message\Bus\MessageBus;

class CreatePostCommandHandler
{
    private $rm;

    private $currentUser;

    private $bus;

    public function __construct(RepositoryManager $rm, User $currentUser, MessageBus $bus)
    {
        $this->rm = $rm;
        $this->currentUser = $currentUser;
        $this->bus = $bus;
    }

    public function handle(CreatePostCommand $command)
    {
        $post = (new Post(new Uuid($command->id), new Uuid((string)$this->currentUser)))
            ->setTitle($command->title)
            ->setBody($command->body)
            ->setIsPublished($command->isPublished);

        /** @var PostRepository $repo */
        $repo = $this->rm->get(Post::class);
        $repo->createPost($post);

        $this->bus->handle(new PostWasCreatedEvent($post));
    }
}
