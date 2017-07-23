<?php

namespace Blog\Security;

use Blog\Entity\User;
use Blog\Entity\ValueObject\Uuid;
use Blog\Service\SearchEngine\Interfaces\SearchEngineInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var SearchEngineInterface
     */
    private $searchEngine;

    public function __construct(SearchEngineInterface $searchEngine)
    {
        $this->searchEngine = $searchEngine;
    }

    /**
     * @inheritdoc
     */
    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @inheritdoc
     */
    public function loadUserByUsername($username)
    {
        return new User(new Uuid('ab5763c9-1d8c-4ad7-b22e-c484c26973d3'));
    }

    /**
     * @inheritdoc
     */
    public function supportsClass($class)
    {
        return User::class == $class;
    }
}
