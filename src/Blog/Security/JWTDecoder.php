<?php

namespace Blog\Security;

use Blog\Security\Interfaces\JWTDecoderInterface;
use Firebase\JWT\JWT;

class JWTDecoder implements JWTDecoderInterface
{
    /**
     * @var string
     */
    private $secretKey;

    /**
     * @var string
     */
    private $algorithm;

    /**
     * @param string $secretKey
     * @param string $algorithm
     */
    public function __construct($secretKey, $algorithm = 'HS256')
    {
        $this->secretKey = $secretKey;
        $this->algorithm = $algorithm;
    }

    /**
     * @param mixed $payload
     *
     * @return string
     */
    public function decode($payload)
    {
        return JWT::decode($payload, $this->secretKey, [$this->algorithm]);
    }
}
