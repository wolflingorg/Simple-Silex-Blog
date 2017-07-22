<?php

namespace Blog\Manager;

use Blog\Entity\User;
use Blog\Entity\ValueObject\Uuid;
use Blog\Manager\Interfaces\UserManagerInterface;

class CurrentUserManager implements UserManagerInterface
{
    /**
     * Returns current user
     *
     * @return User
     */
    public function getUser(): User
    {
        return new User(new Uuid('ab5763c9-1d8c-4ad7-b22e-c484c26973d3'));
    }
}