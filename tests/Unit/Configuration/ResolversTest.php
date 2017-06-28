<?php
namespace Unit\Configuration;

use Blog\Service\Configuration\ConfigurationCollection;
use Blog\Service\Configuration\Loader\YamlFileLoader;
use Blog\Service\Configuration\Resolver\EnvironmentPlaceholdersResolver;
use Blog\Service\Configuration\Resolver\ParameterPlaceholdersResolver;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Resource\FileResource;
use Unit\AbstractUnitTest;

class ResolversTest extends AbstractUnitTest
{
    protected $mocksConfigDir;
    protected $validCollection;

    protected function setUp()
    {
        parent::setUp();

        $this->mocksConfigDir = $this->mocksDir . DIRECTORY_SEPARATOR . 'Configuration';
        $this->validCollection = $this->loadValidCollection();
    }

    protected function loadValidCollection()
    {
        $locator = new FileLocator($this->mocksConfigDir);
        $loader = new YamlFileLoader($locator);

        return $loader->load('valid-config.yml');
    }

    public function testEnvironmentResolverConfig()
    {
        putenv("APP_ENV=TEST");

        $config = (new EnvironmentPlaceholdersResolver($this->validCollection))->getCollection();

        $this->assertEquals($config['environment'], getenv('APP_ENV'));
    }

    public function testParameterResolverConfig()
    {
        $config = (new ParameterPlaceholdersResolver($this->validCollection))->getCollection();

        $this->assertEquals($config['doctrine']['dbal']['host'], $config['parameters']['database_host']);
    }

    public function testAbstractResolverAddResourceConfig()
    {
        $collection = new ParameterPlaceholdersResolver($this->validCollection);

        $this->expectException(\LogicException::class);

        $collection->addResource(new FileResource(__DIR__));
    }

    public function testAbstractResolverAddCollectionConfig()
    {
        $collection = new ParameterPlaceholdersResolver($this->validCollection);

        $this->expectException(\LogicException::class);

        $collection->addCollection(new ConfigurationCollection());
    }

    public function testAbstractResolverGetResourcesConfig()
    {
        $collection = new ParameterPlaceholdersResolver($this->validCollection);

        $this->assertEquals(2, sizeof($collection->getResources()));
    }
}