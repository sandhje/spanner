<?php

namespace Sandhje\Spanner\Test\Resource\Strategy;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Resource\Strategy\XmlStrategy;

class XmlStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testTranslateValid()
    {
        // Arrange
        $testConfigData = array("a" => "b");
        $testConfig = $testConfig = "<bar><a>b</a></bar>";
    
        // Act
        $xmlStrategy = new XmlStrategy();
        $result = $xmlStrategy->translate($testConfig);
    
        // Assert
        $this->assertEquals($testConfigData, $result);
    }
    
    public function testTranslateInvalid()
    {
        // Arrange
        $this->setExpectedException('\Exception');
        $testConfig = "Some invalid config";
    
        // Act
        $xmlStrategy = new XmlStrategy();
        $xmlStrategy->translate($testConfig);
    
        // Assert
        // Intensionally empty, test fails if expected exception is not thrown
    }
    
    public function testTranslateEmpty()
    {
        // Arrange
        $testConfig = false;
    
        // Act
        $xmlStrategy = new XmlStrategy();
        $result = $xmlStrategy->translate($testConfig);
    
        // Assert
        $this->assertEquals(array(), $result);
    }
    
    public function testGetFilename()
    {
        // Arrange
        $region = "bar";
        $file = $region . ".xml";
    
        // Act
        $xmlStrategy = new XmlStrategy();
        $result = $xmlStrategy->getFilename($region);
    
        // Assert
        $this->assertEquals(array($file), $result);
    }
}
