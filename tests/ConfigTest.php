<?php

namespace Sandhje\Spanner\Test;

use Sandhje\Spanner\Config;
use Mockery;
use Sandhje\Spanner\Config\ConfigCollection;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testSetGetPathArray()
    {
        // Arrange
        $pathArray = array("foo/", "bar/");
        $config = new Config();
    
        // Act
        $config->setPathArray($pathArray);
        $resultPathArray = $config->getPathArray();
    
        // Assert
        $this->assertEquals($pathArray, $resultPathArray);
    }
    
    public function testAppendPath()
    {
        // Arrange
        $pathArray = array("foo/", "bar/");
        $config = new Config();
        
        // Act
        $config->appendPath($pathArray[0]);
        $config->appendPath($pathArray[1]);
        $resultPathArray = $config->getPathArray();
        
        // Assert
        $this->assertEquals($pathArray, $resultPathArray);
    }
    
    public function testPrependPath()
    {
        // Arrange
        $pathArray = array("foo/", "bar/");
        $config = new Config();
    
        // Act
        $config->prependPath($pathArray[1]);
        $config->prependPath($pathArray[0]);
        $resultPathArray = $config->getPathArray();
    
        // Assert
        $this->assertEquals($pathArray, $resultPathArray);
    }
    
    public function testSetGetEnvironment()
    {
        // Arrange
        $environment = "foo";
        $config = new Config();
    
        // Act
        $config->setEnvironment($environment);
        $resultEnvironment = $config->getEnvironment();
    
        // Assert
        $this->assertEquals($environment, $resultEnvironment);
    }
    
    public function testGet()
    {
        // Arrange
        $region = "acme";
        $regionArray = array("foo" => "bar");
        $arrayAdapter = Mockery::mock('Sandhje\Spanner\Adapter\ArrayAdapter');
        $arrayAdapter->shouldReceive("load")->with(Mockery::type('Sandhje\Spanner\Config'),$region)->andReturn($regionArray);
        $config = new Config($arrayAdapter);
    
        // Act
        $result = $config->get($region);
    
        // Assert
        $this->assertEquals(new ConfigCollection($region, $regionArray), $result);
    }
    
    public function testSet()
    {
        // Arrange
        $region = "acme";
        $regionArray = array("foo" => "bar");
        $regionArray2 = array("foo" => "lorem");
        $arrayAdapter = Mockery::mock('Sandhje\Spanner\Adapter\ArrayAdapter');
        $arrayAdapter->shouldReceive("load")->with(Mockery::type('Sandhje\Spanner\Config'),$region)->andReturn($regionArray);
        $config = new Config($arrayAdapter);
    
        // Act
        $config->set($region, key($regionArray2), current($regionArray2));
        $result = $config->get($region);
    
        // Assert
        $this->assertEquals(new ConfigCollection($region, $regionArray2), $result);
    }
    
    public function testManualSetItemsRemainAfterClearingCache()
    {
        // Arrange
        $region = "acme";
        $regionArray = array("foo" => "bar");
        $regionArray2 = array("foo" => "lorem");
        $arrayAdapter = Mockery::mock('Sandhje\Spanner\Adapter\ArrayAdapter');
        $arrayAdapter->shouldReceive("load")->with(Mockery::type('Sandhje\Spanner\Config'),$region)->andReturn($regionArray);
        $config = new Config($arrayAdapter);
        
        // Act
        $config->set($region, key($regionArray2), current($regionArray2)); 
        $config->appendPath("test/");
        $result = $config->get($region);
        
        // Assert
        $this->assertEquals(new ConfigCollection($region, $regionArray2), $result);
    }
    
}
