<?php

namespace Sandhje\Spanner\Test\Resource\Strategy;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Resource\Strategy\JsonStrategy;

class JsonStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testTranslateValid()
    {
        // Arrange
        $testConfigData = array("a" => "b");
        $testConfig = json_encode($testConfigData);
    
        // Act
        $jsonStrategy = new JsonStrategy();
        $result = $jsonStrategy->translate($testConfig);
    
        // Assert
        $this->assertEquals($testConfigData, $result);
    }
    
    public function testTranslateInvalid()
    {
        // Arrange
        $this->setExpectedException('\Exception');
        $testConfig = "Some invalid config";
    
        // Act
        $jsonStrategy = new JsonStrategy();
        $jsonStrategy->translate($testConfig);
    
        // Assert
        // Intensionally empty, test fails if expected exception is not thrown
    }
    
    public function testTranslateEmpty()
    {
        // Arrange
        $testConfig = false;
    
        // Act
        $jsonStrategy = new JsonStrategy();
        $result = $jsonStrategy->translate($testConfig);
    
        // Assert
        $this->assertEquals(array(), $result);
    }
    
    public function testGetFilename()
    {
        // Arrange
        $region = "bar";
        $file = $region . ".json";
    
        // Act
        $jsonStrategy = new JsonStrategy();
        $result = $jsonStrategy->getFilename($region);
    
        // Assert
        $this->assertEquals(array($file), $result);
    }
}
