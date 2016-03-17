<?php
namespace Sandhje\Spanner\Test\Resource;

use Mockery;
use Sandhje\Spanner\Resource\LocalFilesystemResource;
use Sandhje\Spanner\Resource\ResourceIterator;

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
        $resource1 = "/foo";
        $resource2 = "/bar";
        $resourceStrategy = Mockery::mock('Sandhje\Spanner\Resource\Strategy\LocalFilesystemFileStrategy');
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_readable')->with($resource1)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($resource2)->andReturn(true);
        $array = array(new LocalFilesystemResource($resource1, $resourceStrategy, $filesystem), new LocalFilesystemResource($resource2, $resourceStrategy, $filesystem));
        $result_keys = array();
        $result_values = array();
        
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