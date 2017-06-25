<?php
namespace Tests\Api;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

abstract class AbstractApiTest extends TestCase
{
    protected function createClient(array $parameters = []) :Client
    {
        return new Client(
            array_replace_recursive(
                $this->getDefaultClientParameters(),
                $parameters
            )
        );
    }

    abstract protected function getDefaultClientParameters() :array;
}