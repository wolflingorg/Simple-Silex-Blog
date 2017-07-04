<?php

namespace Blog\CommandBus\Command;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class CreatePostCommand extends AbstractCommand
{
    public $id;

    public $title;

    public $body;

    public $isPublished = false;

    public $user;

    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('id', new Assert\Uuid());
        $metadata->addPropertyConstraint('id', new Assert\NotBlank());
        $metadata->addPropertyConstraint('title', new Assert\NotBlank());
        $metadata->addPropertyConstraint('title', new Assert\Length(['max' => 256]));
        $metadata->addPropertyConstraint('isPublished', new Assert\Type("bool"));
        $metadata->addPropertyConstraint('user', new Assert\Uuid());
        $metadata->addPropertyConstraint('user', new Assert\NotBlank());
    }
}
