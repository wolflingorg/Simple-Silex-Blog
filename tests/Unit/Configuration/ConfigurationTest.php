<?php
namespace Unit\Configuration;

use Blog\Service\Configuration\Configuration;
use Unit\AbstractUnitTest;

class ConfigurationTest extends AbstractUnitTest
{
    protected $validConfig;

    protected function setUp()
    {
        parent::setUp();

        $this->validConfig = [
            'key' => 'value'
        ];
    }

    public function testConfiguration()
    {
        $config = new Configuration($this->validConfig);

        $this->assertEquals($this->validConfig, $config->getConfiguration());
        $this->assertEquals($this->validConfig['key'], $config['key']);
        $this->assertTrue(isset($config['key']));
    }

    public function testConfigurationOffsetSet()
    {
        $config = new Configuration($this->validConfig);

        $this->expectException(\BadMethodCallException::class);

        $config['new_key'] = 'new_value';
    }

    public function testConfigurationOffsetUnset()
    {
        $config = new Configuration($this->validConfig);

        $this->expectException(\BadMethodCallException::class);

        unset($config['key']);
    }
}