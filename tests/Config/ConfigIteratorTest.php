<?php
namespace Sandhje\Spanner\Test\Config;

use Sandhje\Spanner\Config\ConfigIterator;
/**
 *
 * @author Sandhje
 *        
 */
class ConfigIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testIteratorIndexedArray()
    {
        // Arrange
        $array = array("foo", "bar");
        $result_keys = array();
        $result_values = array();
        
        // Act
        $configIterator = new ConfigIterator($array);
        foreach($configIterator as $key => $value) {
            $result_keys[] = $key;
            $result_values[] = $value;
        }
        
        // Assert
        $this->assertEquals(array_keys($array), $result_keys);
        $this->assertEquals(array_values($array), $result_values);
    }
    
    public function testIteratorAssociativeArray()
    {
        // Arrange
        $array = array("a"=>"foo", "b"=>"bar");
        $result_keys = array();
        $result_values = array();
    
        // Act
        $configIterator = new ConfigIterator($array);
        foreach($configIterator as $key => $value) {
            $result_keys[] = $key;
            $result_values[] = $value;
        }
    
        // Assert
        $this->assertEquals(array_keys($array), $result_keys);
        $this->assertEquals(array_values($array), $result_values);
    }
}

?>