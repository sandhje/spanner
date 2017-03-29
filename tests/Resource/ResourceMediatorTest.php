<?php
namespace Resource;

use OpenSourcerers\Spanner\Resource\ResourceMediator;
use OpenSourcerers\Spanner\Resource\LocalFilesystemResource;
use OpenSourcerers\Spanner\Resource\Strategy\ArrayStrategy;
use Mockery;
use OpenSourcerers\Spanner\Environment\EnvironmentCollection;
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
        $mediator->attach($resource1);
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
        $resource = Mockery::mock('OpenSourcerers\Spanner\Resource\LocalFilesystemResource');
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
        $environmentCollection = new EnvironmentCollection(array($environment));
        $resource = Mockery::mock('OpenSourcerers\Spanner\Resource\LocalFilesystemResource');
        $resource->shouldReceive('tryLoad')->with([], $region)->andReturn(true)->once();
        $resource->shouldReceive('tryLoad')->with([], $region, array($environment))->andReturn(true)->once();
        $mediator = new ResourceMediator();
        $mediator->attach($resource);
    
        // Act
        $mediator->load('foo', $environmentCollection);
    
        // Assert
        // Intensionally empty, test fails if resource load is not called as expected
    }
    
    public function testLoadComposedEnvironment()
    {
        // Arrange
        $region = 'foo';
        $environment = array('bar1', 'bar2', 'bar3');
        $environmentCollectionIterator = array(
            ["bar1"],
            ["bar2"],
            ["bar1", "bar2"],
            ["bar3"],
            ["bar1", "bar3"],
            ["bar1", "bar2", "bar3"],
        );
        $environmentCollection = new EnvironmentCollection($environment);
        $resource = Mockery::mock('OpenSourcerers\Spanner\Resource\LocalFilesystemResource');
        $resource->shouldReceive('tryLoad')->with([], $region)->andReturn(true)->once();
        $resource->shouldReceive('tryLoad')->with([], $region, $environmentCollectionIterator[0])->andReturn(true)->once();
        $resource->shouldReceive('tryLoad')->with([], $region, $environmentCollectionIterator[1])->andReturn(true)->once();
        $resource->shouldReceive('tryLoad')->with([], $region, $environmentCollectionIterator[2])->andReturn(true)->once();
        $resource->shouldReceive('tryLoad')->with([], $region, $environmentCollectionIterator[3])->andReturn(true)->once();
        $resource->shouldReceive('tryLoad')->with([], $region, $environmentCollectionIterator[4])->andReturn(true)->once();
        $resource->shouldReceive('tryLoad')->with([], $region, $environmentCollectionIterator[5])->andReturn(true)->once();
        $mediator = new ResourceMediator();
        $mediator->attach($resource);
    
        // Act
        $mediator->load('foo', $environmentCollection);
    
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