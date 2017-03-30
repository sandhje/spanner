<?php
namespace OpenSourcerers\Spanner\Test\Resource;

use Mockery;
use OpenSourcerers\Spanner\Resource\LocalFilesystemResource;
use OpenSourcerers\Spanner\Resource\ResourceIterator;
use OpenSourcerers\Spanner\Resource\Strategy\ArrayStrategy;

/**
 *
 * @author Sandhje
 *        
 */
class ResourceIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testIteratorArray()
    {
        // Arrange
        $resource1 = new LocalFilesystemResource("/foo", new ArrayStrategy()); 
        $resource2 = new LocalFilesystemResource("/bar", new ArrayStrategy());
        $array = array($resource1, $resource2);
        $result_keys = [];
        $result_values = [];
        
        // Act
        $resourceIterator = new ResourceIterator($array);
        foreach($resourceIterator as $key => $value) {
            $result_keys[] = $key;
            $result_values[] = $value;
        }
        
        // Assert
        $this->assertEquals(array_keys($array), $result_keys);
        $this->assertEquals($array, $result_values);
    }
}

?>