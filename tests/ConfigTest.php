<?php

namespace OpenSourcerers\Spanner\Test;

use OpenSourcerers\Spanner\Config;
use Mockery;
use OpenSourcerers\Spanner\Config\ConfigCollection;
use OpenSourcerers\Spanner\Resource\LocalFilesystemResource;
use OpenSourcerers\Spanner\Resource\Strategy\ArrayStrategy;
use OpenSourcerers\Spanner\Environment\EnvironmentCollection;

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
        $mediator = Mockery::mock('OpenSourcerers\Spanner\Resource\ResourceMediator');
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
        $mediator = Mockery::mock('OpenSourcerers\Spanner\Resource\ResourceMediator');
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
        $environmentCollection = new EnvironmentCollection(array($environment));
        $config = new Config();
    
        // Act
        $config->setEnvironment($environment);
        $resultEnvironment = $config->getEnvironment();
    
        // Assert
        $this->assertEquals($environmentCollection, $resultEnvironment);
    }
    
    public function testGetEmptyEnvironment()
    {
        // Arrange
        $config = new Config();
        
        // Act
        $resultEnvironment = $config->getEnvironment();
        
        // Assert
        $this->assertNull($resultEnvironment);
    }
    
    public function testGet()
    {
        // Arrange
        $region = "acme";
        $regionArray = array("foo" => "bar");
        $mediator = Mockery::mock('OpenSourcerers\Spanner\Resource\ResourceMediator');
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
        $mediator = Mockery::mock('OpenSourcerers\Spanner\Resource\ResourceMediator');
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
        $mediator = Mockery::mock('OpenSourcerers\Spanner\Resource\ResourceMediator');
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
