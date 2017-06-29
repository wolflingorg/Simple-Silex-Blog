<?php

namespace Unit\Configuration;

use Blog\Service\Configuration\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use Unit\AbstractUnitTest;

class YamlFileLoaderTest extends AbstractUnitTest
{
    protected $mocksConfigDir;

    protected function setUp()
    {
        parent::setUp();

        $this->mocksConfigDir = $this->mocksDir . DIRECTORY_SEPARATOR . 'Configuration';
    }

    public function testLoadValidYamlConfig()
    {
        $validConfig = json_decode('{"parameters":{"database_host":"127.0.0.1","database_port":3306,"database_name":"symfony","database_user":"root","database_password":"root"},"doctrine":{"dbal":{"driver":"pdo_mysql","host":"%database_host%","port":"%database_port%","dbname":"%database_name%","user":"%database_user%","password":"%database_password%","charset":"UTF8"}},"environment":"%env(APP_ENV)%"}', true);

        $locator = new FileLocator($this->mocksConfigDir);
        $loader = new YamlFileLoader($locator);
        $collection = $loader->load('valid-config.yml');

        $this->assertEquals($collection->getCollection(), $validConfig);
    }

    public function testLoadEmptyYamlConfig()
    {
        $locator = new FileLocator($this->mocksConfigDir);
        $loader = new YamlFileLoader($locator);

        $collection = $loader->load('empty-config.yml');

        $this->assertEmpty($collection->getCollection());
    }

    public function testLoadInValidYamlConfig()
    {
        $locator = new FileLocator($this->mocksConfigDir);
        $loader = new YamlFileLoader($locator);

        $this->expectException(\InvalidArgumentException::class);

        $loader->load('invalid-config.yml');
    }

    public function testLoadInValidImportsYamlConfig()
    {
        $locator = new FileLocator($this->mocksConfigDir);
        $loader = new YamlFileLoader($locator);

        $this->expectException(\InvalidArgumentException::class);

        $loader->load('invalid-imports-config.yml');
    }

    public function testLoadInValidImportsResourcesYamlConfig()
    {
        $locator = new FileLocator($this->mocksConfigDir);
        $loader = new YamlFileLoader($locator);

        $this->expectException(\InvalidArgumentException::class);

        $loader->load('invalid-imports-resource-config.yml');
    }
}