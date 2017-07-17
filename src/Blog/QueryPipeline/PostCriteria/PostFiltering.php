<?php

namespace Blog\QueryPipeline\PostCriteria;

use Blog\Service\SearchEngine\Abstracts\AbstractFiltering;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class PostFiltering extends AbstractFiltering
{
    public $id;

    public $title;

    public $body;

    public $isPublished;

    public $user;

    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('id', new Assert\Uuid());
        $metadata->addPropertyConstraint('user', new Assert\Uuid());
        $metadata->addPropertyConstraint('title', new Assert\Length(['max' => 256]));
        $metadata->addPropertyConstraint('body', new Assert\Length(['max' => 256]));
        $metadata->addPropertyConstraint('isPublished', new Assert\Choice([1, 0]));
    }
}
