<?php

namespace Blog\Manager\Interfaces;

use Blog\Entity\User;

interface UserManagerInterface
{
    /**
     * @return User
     */
    public function getUser(): User;

    /**
     * @param User $user
     */
    public function setUser(User $user);
}