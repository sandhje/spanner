<?php

namespace Sandhje\Spanner\Test\Resource\Strategy;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Resource\Strategy\ArrayStrategy;

class ArrayStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testTranslateValid()
    {
        // Arrange
        $testConfig = array("a" => "b");
        
        // Act
        $arrayStrategy = new ArrayStrategy();
        $result = $arrayStrategy->translate($testConfig);
        
        // Assert
        $this->assertEquals($testConfig, $result);
    }
    
    public function testTranslateInvalid()
    {
        // Arrange
        $this->setExpectedException('\Exception');
        $testConfig = "Some invalid config";
    
        // Act
        $arrayStrategy = new ArrayStrategy();
        $arrayStrategy->translate($testConfig);
    
        // Assert
        // Intensionally empty, test fails if expected exception is not thrown
    }
    
    public function testGetFilename()
    {
        // Arrange
        $region = "bar";
        $file = $region . ".php";
        
        // Act
        $arrayStrategy = new ArrayStrategy();
        $result = $arrayStrategy->getFilename($region);
        
        // Assert
        $this->assertEquals($file, $result);
    }
}
