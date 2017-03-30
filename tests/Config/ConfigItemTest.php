<?php
namespace OpenSourcerers\Spanner\Test\Config;

use OpenSourcerers\Spanner\Config\ConfigItem;
/**
 *
 * @author Sandhje
 *        
 */
class ConfigItemTest extends \PHPUnit_Framework_TestCase
{
    public function testGetKey()
    {
        // Arrange
        $region = "acme";
        $key = "foo";
        $value = "bar";
        
        // Act
        $configItem = new ConfigItem($region, $key, $value);
        $result = $configItem->getKey();
        
        // Assert
        $this->assertEquals($key, $result);
    }
    
    public function testGetValue()
    {
        // Arrange
        $region = "acme";
        $key = "foo";
        $value = "bar";
    
        // Act
        $configItem = new ConfigItem($region, $key, $value);
        $result = $configItem->getValue();
    
        // Assert
        $this->assertEquals($value, $result);
    }
}

?>