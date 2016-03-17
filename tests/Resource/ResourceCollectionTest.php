<?php
namespace Sandhje\Spanner\Test\Resource;

use Sandhje\Spanner\Resource\LocalFilesystemResource;
use Sandhje\Spanner\Resource\ResourceCollection;
use Mockery;

/**
 *
 * @author Sandhje
 *        
 */
class ResourceCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testToArray()
    {
        // Arrange
        $resource1 = "/foo";
        $resource2 = "/bar";
        $resourceStrategy = Mockery::mock('Sandhje\Spanner\Resource\Strategy\LocalFilesystemFileStrategy');
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_readable')->with($resource1)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($resource2)->andReturn(true);
        $array = array(new LocalFilesystemResource($resource1, $resourceStrategy, $filesystem), new LocalFilesystemResource($resource2, $resourceStrategy, $filesystem));
        
        // Act
        $resourceCollection = new ResourceCollection($array);
        $result = $resourceCollection->toArray();
        
        // Assert
        $this->assertEquals($array, $result);
    }
    
    public function testGetResource()
    {
        // Arrange
        $resource1 = "/foo";
        $resource2 = "/bar";
        $resourceStrategy = Mockery::mock('Sandhje\Spanner\Resource\Strategy\LocalFilesystemFileStrategy');
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_readable')->with($resource1)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($resource2)->andReturn(true);
        $array = array(new LocalFilesystemResource($resource1, $resourceStrategy, $filesystem), new LocalFilesystemResource($resource2, $resourceStrategy, $filesystem));
        
        // Act
        $resourceCollection = new ResourceCollection($array);
        $result = $resourceCollection->get(0);
        
        // Assert
        $this->assertEquals($array[0], $result);
    }
    
    public function testGetIterator()
    {
        // Arrange
        $resource1 = "/foo";
        $resource2 = "/bar";
        $resourceStrategy = Mockery::mock('Sandhje\Spanner\Resource\Strategy\LocalFilesystemFileStrategy');
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_readable')->with($resource1)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($resource2)->andReturn(true);
        $array = array(new LocalFilesystemResource($resource1, $resourceStrategy, $filesystem), new LocalFilesystemResource($resource2, $resourceStrategy, $filesystem));
        
        // Act
        $resourceCollection = new ResourceCollection($array);
        $iterator = $resourceCollection->getIterator();
        
        // Assert
        $this->assertInstanceOf('Sandhje\Spanner\Resource\ResourceIterator', $iterator);
    }
}

?>