<?php

namespace Blog\Security;

use Blog\Entity\User;
use Blog\Repository\Criteria\UserCriteria;
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
        $criteria = new UserCriteria(['id' => $username]);
        $result = $this->searchEngine->match($criteria);
        if (isset($result->getResult()[0]) && $result->getResult()[0] instanceof User) {
            return $result->getResult()[0];
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function supportsClass($class)
    {
        return User::class == $class;
    }
}
