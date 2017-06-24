<?php
namespace Blog\Service\CommandBus;

interface EventHandler
{
    public function notify(Event $command);
}