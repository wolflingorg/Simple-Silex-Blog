<?php

namespace Blog\Manager\Interfaces;

use Blog\Entity\User;

interface UserManagerInterface
{
    public function getUser(): User;
}