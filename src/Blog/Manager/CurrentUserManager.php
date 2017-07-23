<?php

namespace Blog\Manager;

use Blog\Entity\User;
use Blog\Manager\Interfaces\UserManagerInterface;

class CurrentUserManager implements UserManagerInterface
{
    private $user;

    /**
     * Returns current user
     *
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * Set current user
     *
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}