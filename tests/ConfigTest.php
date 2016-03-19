<?php

namespace Sandhje\Spanner\Test;

use Sandhje\Spanner\Config;
use Mockery;
use Sandhje\Spanner\Config\ConfigCollection;
use Sandhje\Spanner\Test\Mock\MockFactory;
use Sandhje\Spanner\Resource\ResourceCollection;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    private $mockFactory;
    
    public function setUp()
    {
        $this->mockFactory = new MockFactory();
    }
    
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testSetGetResourceArray()
    {
        // Arrange
        $resourceArray = array (
            $this->mockFactory->getMockLocalFilesystemDirResource("foo/"),
            $this->mockFactory->getMockLocalFilesystemDirResource("bar/")
        );
    
        // Act
        $config = new Config();
        $config->setResourceArray($resourceArray);
        $resultResourceArray = $config->getResourceCollection();
    
        // Assert
        $this->assertEquals(new ResourceCollection($resourceArray), $resultResourceArray);
    }
    
    public function testAppendResource()
    {
        // Arrange
        $resourceArray = array (
            $this->mockFactory->getMockLocalFilesystemDirResource("foo/"),
            $this->mockFactory->getMockLocalFilesystemDirResource("bar/")
        );
        $config = new Config();
        
        // Act
        $config->appendResource($resourceArray[0]);
        $config->appendResource($resourceArray[1]);
        $resultResourceCollection = $config->getResourceCollection();
        
        // Assert
        $this->assertEquals(new ResourceCollection($resourceArray), $resultResourceCollection);
    }
    
    public function testPrependResource()
    {
        // Arrange
        $resourceArray = array (
            $this->mockFactory->getMockLocalFilesystemDirResource("foo/"),
            $this->mockFactory->getMockLocalFilesystemDirResource("bar/")
        );
        $config = new Config();
    
        // Act
        $config->prependResource($resourceArray[1]);
        $config->prependResource($resourceArray[0]);
        $resultResourceCollection = $config->getResourceCollection();
    
        // Assert
        $this->assertEquals(new ResourceCollection($resourceArray), $resultResourceCollection);
    }
    
    public function testSetGetEnvironment()
    {
        // Arrange
        $environment = "foo";
        $config = new Config();
    
        // Act
        $config->setEnvironment($environment);
        $resultEnvironment = $config->getEnvironment();
    
        // Assert
        $this->assertEquals($environment, $resultEnvironment);
    }
    
    public function testGet()
    {
        // Arrange
        $region = "acme";
        $regionArray = array("foo" => "bar");
        $arrayAdapter = Mockery::mock('Sandhje\Spanner\Adapter\ArrayAdapter');
        $arrayAdapter->shouldReceive("load")->with(Mockery::type('Sandhje\Spanner\Config'),$region)->andReturn($regionArray);
        $config = new Config($arrayAdapter);
    
        // Act
        $result = $config->get($region);
    
        // Assert
        $this->assertEquals(new ConfigCollection($region, $regionArray), $result);
    }
    
    public function testSet()
    {
        // Arrange
        $region = "acme";
        $regionArray = array("foo" => "bar");
        $regionArray2 = array("foo" => "lorem");
        $arrayAdapter = Mockery::mock('Sandhje\Spanner\Adapter\ArrayAdapter');
        $arrayAdapter->shouldReceive("load")->with(Mockery::type('Sandhje\Spanner\Config'),$region)->andReturn($regionArray);
        $config = new Config($arrayAdapter);
    
        // Act
        $config->set($region, key($regionArray2), current($regionArray2));
        $result = $config->get($region);
    
        // Assert
        $this->assertEquals(new ConfigCollection($region, $regionArray2), $result);
    }
    
    public function testManualSetItemsRemainAfterClearingCache()
    {
        // Arrange
        $region = "acme";
        $regionArray = array("foo" => "bar");
        $regionArray2 = array("foo" => "lorem");
        $arrayAdapter = Mockery::mock('Sandhje\Spanner\Adapter\ArrayAdapter');
        $arrayAdapter->shouldReceive("load")->with(Mockery::type('Sandhje\Spanner\Config'),$region)->andReturn($regionArray);
        $resource = $this->mockFactory->getMockLocalFilesystemDirResource("test/");
        
        // Act
        $config = new Config($arrayAdapter);
        $config->set($region, key($regionArray2), current($regionArray2)); 
        $config->appendResource($resource);
        $result = $config->get($region);
        
        // Assert
        $this->assertEquals(new ConfigCollection($region, $regionArray2), $result);
    }
}
