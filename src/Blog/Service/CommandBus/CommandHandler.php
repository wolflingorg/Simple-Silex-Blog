<?php
namespace Blog\Service\CommandBus;

interface CommandHandler
{
    public function handle(Command $command);
}