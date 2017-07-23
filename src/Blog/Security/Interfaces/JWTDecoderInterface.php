<?php

namespace Blog\Security\Interfaces;

interface JWTDecoderInterface
{
    public function decode($payload);
}
