<?php
namespace OpenSourcerers\Spanner\Test\Config;

use OpenSourcerers\Spanner\Config\ConfigCollection;
use OpenSourcerers\Spanner\Config\ConfigItem;
use OpenSourcerers\Spanner\Config\ConfigIterator;
/**
 *
 * @author Sandhje
 *        
 */
class ConfigCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testToArray()
    {
        // Arrange
        $region = "acme";
        $array = array("a" => "foo", "b" => "bar");
        
        // Act
        $configCollection = new ConfigCollection($region, $array);
        $result = $configCollection->toArray();
        
        // Assert
        $this->assertEquals($array, $result);
    }
    
    public function testGetCollection()
    {
        // Arrange
        $region = "acme";
        $innerArray = array("foo" => "bar");
        $array = array("a" => $innerArray);
        
        // Act
        $configCollection = new ConfigCollection($region, $array);
        $result = $configCollection->get("a");
        
        // Assert
        $this->assertEquals(new ConfigCollection($region, $innerArray), $result);
    }
    
    public function testGetItem()
    {
        // Arrange
        $region = "acme";
        $array = array("foo" => "bar");
        
        // Act
        $configCollection = new ConfigCollection($region, $array);
        $result = $configCollection->get("foo");
        
        // Assert
        $this->assertEquals(new ConfigItem($region, "foo", "bar"), $result);
    }
    
    public function testGetIterator()
    {
        // Arrange
        $region = "acme";
        $array = array("foo" => "bar");
        
        // Act
        $configCollection = new ConfigCollection($region, $array);
        $iterator = $configCollection->getIterator();
        
        // Assert
        $this->assertInstanceOf('OpenSourcerers\Spanner\Config\ConfigIterator', $iterator);
    }
}

?>