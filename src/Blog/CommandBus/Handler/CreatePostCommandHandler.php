<?php

namespace Blog\CommandBus\Handler;

use Blog\CommandBus\Command\CreatePostCommand;

class CreatePostCommandHandler
{
    public function handle(CreatePostCommand $command)
    {
        print_r($command);
    }
}
