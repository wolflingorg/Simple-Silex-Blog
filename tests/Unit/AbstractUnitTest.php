<?php

namespace Unit;

use PHPUnit\Framework\TestCase;

abstract class AbstractUnitTest extends TestCase
{
    protected $mocksDir;

    protected function setUp()
    {
        $this->mocksDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Mocks';
    }
}