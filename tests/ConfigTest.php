<?php

namespace Sandhje\Spanner\Test;

use Sandhje\Spanner\Config;
use Mockery;
use Sandhje\Spanner\Config\ConfigCollection;
use Sandhje\Spanner\Resource\ResourceCollection;
use Sandhje\Spanner\Resource\LocalFilesystemResource;
use Sandhje\Spanner\Resource\Strategy\ArrayStrategy;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testAttachResource()
    {
        // Arrange
        $resource1 = new LocalFilesystemResource("foo/", new ArrayStrategy());
        $resource2 = new LocalFilesystemResource("bar/", new ArrayStrategy());
        $mediator = Mockery::mock('Sandhje\Spanner\Resource\ResourceMediator');
        $mediator->shouldReceive('attach')->twice()->andReturn("1", "2");
        $config = new Config();
        $config->setResourceMediator($mediator);
        
        // Act
        $result1 = $config->attachResource($resource1);
        $result2 = $config->attachResource($resource2, true);
        
        // Assert
        $this->assertEquals($config, $result1);
        $this->assertEquals("2", $result2);
    }
    
    public function testDetachResource()
    {
        // Arrange
        $mediator = Mockery::mock('Sandhje\Spanner\Resource\ResourceMediator');
        $mediator->shouldReceive('detach')->with("1")->once();        
        $config = new Config();
        $config->setResourceMediator($mediator);
    
        // Act
        $config->detachResource("1");
    
        // Assert
        // Intensionally empty - the test will fail if the expected method on the mocked mediator is not called
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
        $mediator = Mockery::mock('Sandhje\Spanner\Resource\ResourceMediator');
        $mediator->shouldReceive("load")->with($region, null)->andReturn($regionArray);
        $config = new Config();
        $config->setResourceMediator($mediator);
    
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
        $mediator = Mockery::mock('Sandhje\Spanner\Resource\ResourceMediator');
        $mediator->shouldReceive("load")->with($region, null)->andReturn($regionArray);
        $config = new Config();
        $config->setResourceMediator($mediator);
    
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
        $mediator = Mockery::mock('Sandhje\Spanner\Resource\ResourceMediator');
        $mediator->shouldReceive("attach");
        $mediator->shouldReceive("load")->with($region, null)->andReturn($regionArray);
        $config = new Config();
        $config->setResourceMediator($mediator);
        $resource = new LocalFilesystemResource("/foo", new ArrayStrategy());
        
        // Act
        $config->set($region, key($regionArray2), current($regionArray2)); 
        $config->attachResource($resource);
        $result = $config->get($region);
        
        // Assert
        $this->assertEquals(new ConfigCollection($region, $regionArray2), $result);
    }
}
