<?php
namespace Resource;

use Sandhje\Spanner\Resource\ResourceMediator;
use Sandhje\Spanner\Resource\LocalFilesystemResource;
use Sandhje\Spanner\Resource\Strategy\ArrayStrategy;
use Sandhje\Spanner\Resource\ResourceCollection;
use Sandhje\Spanner\Resource\LocalFilesystemResource\LocalFilesystemDirectoryState;
use Mockery;
/**
 *
 * @author Sandhje
 *        
 */
class ResourceMediatorTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testAttach()
    {
        // Arrange
        $resource = new LocalFilesystemResource("/foo", new ArrayStrategy());
        $mediator = new ResourceMediator();
        
        // Act
        $key = $mediator->attach($resource);
        $resultCollection = $mediator->getResourceCollection();
        
        // Assert
        $this->assertEquals($resource, $resultCollection->get($key));
    }
    
    public function testDetach()
    {
        // Arrange
        $resource1 = new LocalFilesystemResource("/foo", new ArrayStrategy());
        $resource2 = new LocalFilesystemResource("/bar", new ArrayStrategy());
        $mediator = new ResourceMediator();
    
        // Act
        $key1 = $mediator->attach($resource1);
        $key2 = $mediator->attach($resource2);
        $resultCollection1 = $mediator->getResourceCollection();
        $mediator->detach($key2);
        $resultCollection2 = $mediator->getResourceCollection();
    
        // Assert
        $this->assertEquals($resource2, $resultCollection1->get($key2));
        $this->assertFalse($resultCollection2->get($key2));
    }
    
    public function testLoadNoEnvironment()
    {
        // Arrange
        $region = 'foo';
        $resource = Mockery::mock('Sandhje\Spanner\Resource\LocalFilesystemResource');
        $resource->shouldReceive('tryLoad')->with([], $region)->andReturn(true)->once();
        $mediator = new ResourceMediator();
        $mediator->attach($resource);
    
        // Act
        $mediator->load('foo', null);
    
        // Assert
        // Intensionally empty, test fails if resource load is not called as expected 
    }
    
    public function testLoadSingleEnvironment()
    {
        // Arrange
        $region = 'foo';
        $environment = 'bar';
        $resource = Mockery::mock('Sandhje\Spanner\Resource\LocalFilesystemResource');
        $resource->shouldReceive('tryLoad')->with([], $region)->andReturn(true)->once();
        $resource->shouldReceive('tryLoad')->with([], $region, array($environment))->andReturn(true)->once();
        $mediator = new ResourceMediator();
        $mediator->attach($resource);
    
        // Act
        $mediator->load('foo', $environment);
    
        // Assert
        // Intensionally empty, test fails if resource load is not called as expected
    }
    
    public function testLoadComposedEnvironment()
    {
        // Arrange
        $region = 'foo';
        $environment = array('bar1', 'bar2', 'bar3');
        $resource = Mockery::mock('Sandhje\Spanner\Resource\LocalFilesystemResource');
        $resource->shouldReceive('tryLoad')->with([], $region)->andReturn(true)->once();
        $resource->shouldReceive('tryLoad')->with([], $region, array_slice($environment, 0, 1))->andReturn(true)->once();
        $resource->shouldReceive('tryLoad')->with([], $region, array_slice($environment, 0, 2))->andReturn(true)->once();
        $resource->shouldReceive('tryLoad')->with([], $region, array_slice($environment, 0, 3))->andReturn(true)->once();
        $mediator = new ResourceMediator();
        $mediator->attach($resource);
    
        // Act
        $mediator->load('foo', $environment);
    
        // Assert
        // Intensionally empty, test fails if resource load is not called as expected
    }
    
    public function testMerge()
    {
        // Arrange
        $array1 = array("a" => array("b" => "lorem", "c" => "ipsum"));
        $array2 = array("a" => array("c" => "dolor", "d" => "sit amet"));
        $expected = array("a" => array("b" => "lorem", "c" => "dolor", "d" => "sit amet"));
        $mediator = new ResourceMediator();
        
        // Act
        $result = $mediator->merge($array1, $array2);
        
        // Assert
        $this->assertEquals($expected, $result);
    }
}

?>