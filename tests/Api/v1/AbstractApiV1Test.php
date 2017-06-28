<?php
namespace Tests\Api\v1;

use Tests\Api\AbstractApiTest;

abstract class AbstractApiV1Test extends AbstractApiTest
{
    const API_URL = 'http://localhost:8000/api/v1/';

    protected function getDefaultClientParameters(): array
    {
        return [
            'base_uri' => self::API_URL,
        ];
    }
}