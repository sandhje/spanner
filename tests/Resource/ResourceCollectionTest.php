<?php
namespace Sandhje\Spanner\Test\Resource;

use Sandhje\Spanner\Resource\LocalFilesystemResource;
use Sandhje\Spanner\Resource\ResourceCollection;
use Mockery;
use Sandhje\Spanner\Resource\Strategy\ArrayStrategy;

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
        $resource1 = new LocalFilesystemResource("/foo", new ArrayStrategy());
        $resource2 = new LocalFilesystemResource("/bar", new ArrayStrategy());
        $array = array(
            $resource1, 
            $resource2
        );
        
        // Act
        $resourceCollection = new ResourceCollection($array);
        $result = $resourceCollection->toArray();
        
        // Assert
        $this->assertEquals($array, $result);
    }
    
    public function testGetResource()
    {
        // Arrange
        $resource1 = new LocalFilesystemResource("/foo", new ArrayStrategy());
        $resource2 = new LocalFilesystemResource("/bar", new ArrayStrategy());
        $array = array(
            $resource1,
            $resource2
        );
        
        // Act
        $resourceCollection = new ResourceCollection($array);
        $result = $resourceCollection->get(0);
        
        // Assert
        $this->assertEquals($array[0], $result);
    }
    
    public function testGetIterator()
    {
        // Arrange
        $resource1 = new LocalFilesystemResource("/foo", new ArrayStrategy());
        $resource2 = new LocalFilesystemResource("/bar", new ArrayStrategy());
        $array = array(
            $resource1,
            $resource2
        );
        
        // Act
        $resourceCollection = new ResourceCollection($array);
        $iterator = $resourceCollection->getIterator();
        
        // Assert
        $this->assertInstanceOf('Sandhje\Spanner\Resource\ResourceIterator', $iterator);
    }
}

?>