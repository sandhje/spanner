<?php
namespace OpenSourcerers\Spanner\Test\Config;

use OpenSourcerers\Spanner\Config\ConfigElement;
/**
 *
 * @author Sandhje
 *        
 */
class ConfigElementTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSetRegion()
    {
        // Arrange
        $region = "foo";
        
        // Act
        $configElement = new ConcreteConfigElement($region);
        $result = $configElement->getRegion();
        
        // Assert
        $this->assertEquals($region, $result);
    }
}

class ConcreteConfigElement extends ConfigElement
{
    public function __construct($region) {
        parent::__construct($region);
    }
}

?>