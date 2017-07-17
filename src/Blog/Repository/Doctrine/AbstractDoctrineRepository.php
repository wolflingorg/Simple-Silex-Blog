<?php

namespace Blog\Repository\Doctrine;

use Blog\Repository\Interfaces\RepositoryInterface;
use Doctrine\ORM\EntityManager;

abstract class AbstractDoctrineRepository implements RepositoryInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function persist($object)
    {
        $this->em->persist($object);
    }
}
