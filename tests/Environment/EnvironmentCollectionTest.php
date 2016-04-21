<?php
namespace Sandhje\Spanner\Test\Environment;

use Mockery;
use Sandhje\Spanner\Environment\EnvironmentCollection;
/**
 *
 * @author Sandhje
 *        
 */
class EnvironmentCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testToArray()
    {
        // Arrange
        $array = array(
            "foo", 
            "bar"
        );
        
        // Act
        $environmentCollection = new EnvironmentCollection($array);
        $result = $environmentCollection->toArray();
        
        // Assert
        $this->assertEquals($array, $result);
    }
    
    public function testGetIterator()
    {
        // Arrange
        $array = array(
            "foo",
            "bar"
        );
        
        // Act
        $environmentCollection = new EnvironmentCollection($array);
        $iterator = $environmentCollection->getIterator();
        
        // Assert
        $this->assertInstanceOf('Sandhje\Spanner\Environment\EnvironmentIterator', $iterator);
    }
}

?>