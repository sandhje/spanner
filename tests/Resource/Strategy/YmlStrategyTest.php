<?php

namespace Sandhje\Spanner\Test\Resource\Strategy;

use Sandhje\Spanner\Config;
use Symfony\Component\Yaml\Yaml;
use Sandhje\Spanner\Resource\Strategy\YamlStrategy;

class YmlStrategyTest extends \PHPUnit_Framework_TestCase
{   
    public function testTranslateValid()
    {
        // Arrange
        $testConfigData = array("a" => "b");
        $testConfig = Yaml::dump($testConfigData);
    
        // Act
        $ymlStrategy = new YamlStrategy();
        $result = $ymlStrategy->translate($testConfig);
    
        // Assert
        $this->assertEquals($testConfigData, $result);
    }
    
    public function testTranslateInvalid()
    {
        // Arrange
        $this->setExpectedException('\Exception');
        $testConfig = <<<YAML
foo:
    - bar
"missing colon"
    foo: bar
YAML;
    
        // Act
        $ymlStrategy = new YamlStrategy();
        $ymlStrategy->translate($testConfig);
    
        // Assert
        // Intensionally empty, test fails if expected exception is not thrown
    }
    
    public function testTranslateEmpty()
    {
        // Arrange
        $testConfig = false;
    
        // Act
        $yamlStrategy = new YamlStrategy();
        $result = $yamlStrategy->translate($testConfig);
    
        // Assert
        $this->assertEquals(array(), $result);
    }
    
    public function testGetFilename()
    {
        // Arrange
        $region = "bar";
        $files = [$region . ".yml", $region . ".yaml"];
    
        // Act
        $ymlStrategy = new YamlStrategy();
        $result = $ymlStrategy->getFilename($region);
    
        // Assert
        $this->assertEquals($files, $result);
    }
}
