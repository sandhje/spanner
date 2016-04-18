<?php

namespace Sandhje\Spanner\Test\Resource\Strategy;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Resource\Strategy\IniStrategy;

class IniStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testTranslateValid()
    {
        // Arrange
        $testConfigData = array("a" => "b");
        $testConfig = 'a=b';
    
        // Act
        $iniStrategy = new IniStrategy();
        $result = $iniStrategy->translate($testConfig);
    
        // Assert
        $this->assertEquals($testConfigData, $result);
    }
    
    public function testTranslateInvalid()
    {
        // Arrange
        $this->setExpectedException('\Exception');
        $testConfig = "Some invalid config";
    
        // Act
        $iniStrategy = new IniStrategy();
        $iniStrategy->translate($testConfig);
    
        // Assert
        // Intensionally empty, test fails if expected exception is not thrown
    }
    
    public function testTranslateEmpty()
    {
        // Arrange
        $testConfig = false;
    
        // Act
        $iniStrategy = new IniStrategy();
        $result = $iniStrategy->translate($testConfig);
    
        // Assert
        $this->assertEquals(array(), $result);
    }
    
    public function testGetFilename()
    {
        // Arrange
        $region = "bar";
        $file = $region . ".ini";
    
        // Act
        $iniStrategy = new IniStrategy();
        $result = $iniStrategy->getFilename($region);
    
        // Assert
        $this->assertEquals($file, $result);
    }
}
