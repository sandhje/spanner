<?php
namespace Sandhje\Spanner\Test\Resource;

use Mockery;
use Sandhje\Spanner\Resource\LocalFilesystemResource;
use Sandhje\Spanner\Resource\ResourceIterator;
use Sandhje\Spanner\Resource\Strategy\ArrayStrategy;

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