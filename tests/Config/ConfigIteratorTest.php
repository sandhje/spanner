<?php
namespace Sandhje\Spanner\Test\Config;

use Sandhje\Spanner\Config\ConfigIterator;
use Sandhje\Spanner\Config\ConfigItem;
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
        $region = "acme";
        $array = array("foo", "bar");
        $result_keys = array();
        $result_values = array();
        
        // Act
        $configIterator = new ConfigIterator($region, $array);
        foreach($configIterator as $key => $value) {
            $result_keys[] = $key;
            $result_values[] = $value;
        }
        
        // Assert
        $this->assertEquals(array_keys($array), $result_keys);
        $this->assertEquals(array(new ConfigItem($region, 0, "foo"), new ConfigItem($region, 1, "bar")), $result_values);
    }
    
    public function testIteratorAssociativeArray()
    {
        // Arrange
        $region = "acme";
        $array = array("a"=>"foo", "b"=>"bar");
        $result_keys = array();
        $result_values = array();
    
        // Act
        $configIterator = new ConfigIterator($region, $array);
        foreach($configIterator as $key => $value) {
            $result_keys[] = $key;
            $result_values[] = $value;
        }
    
        // Assert
        $this->assertEquals(array_keys($array), $result_keys);
        $this->assertEquals(array(new ConfigItem($region, "a", "foo"), new ConfigItem($region, "b", "bar")), $result_values);
    }
}

?>