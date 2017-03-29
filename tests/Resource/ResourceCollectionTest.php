<?php
namespace OpenSourcerers\Spanner\Test\Resource;

use OpenSourcerers\Spanner\Resource\LocalFilesystemResource;
use OpenSourcerers\Spanner\Resource\ResourceCollection;
use Mockery;
use OpenSourcerers\Spanner\Resource\Strategy\ArrayStrategy;

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
        $this->assertInstanceOf('OpenSourcerers\Spanner\Resource\ResourceIterator', $iterator);
    }
}

?>