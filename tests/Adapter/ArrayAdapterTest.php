<?php

namespace Sandhje\Spanner\Test\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\ArrayAdapter;
use Mockery;
use Sandhje\Spanner\Test\Mock\MockFactory;

class ArrayAdapterTest extends \PHPUnit_Framework_TestCase
{
    private $mockFactory;
    
    public function setUp()
    {
        $this->mockFactory = new MockFactory();
    }
    
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testLoadRegion()
    {
        // Arrange
        $path = "/foo";
        $region = "bar";
        $file = $region . ".php";
        $testConfig = array("a" => "b");
        $resource = $this->mockFactory->getMockLocalFilesystemDirResource($path);
        $resource->shouldReceive('loadFile')->with($file, false)->andReturn($testConfig);
        
        // Act
        $config = new Config();
        $config->appendResource($resource);
        $arrayAdapter = new ArrayAdapter();
        $result = $arrayAdapter->load($config, $region);
        
        // Assert
        $this->assertEquals($testConfig, $result);
    }
    
    public function testLoadRegionWithEnvironment()
    {
        // Arrange
        $path = "/foo";
        $region = "bar";
        $env = "test";
        $file = $region . ".php";
        $testConfig = array("a" => "b");
        $testEnvConfig = array("c" => "d");
        $resource = $this->mockFactory->getMockLocalFilesystemDirResource($path);
        $resource->shouldReceive('loadFile')->with($file, false)->andReturn($testConfig);
        $resource->shouldReceive('loadFile')->with($file, $env)->andReturn($testEnvConfig);
        
        // Act
        $config = new Config();
        $config->appendResource($resource);
        $config->setEnvironment($env);
        $arrayAdapter = new ArrayAdapter();
        $result = $arrayAdapter->load($config, $region);
        
        // Assert
        $resultConfig = array("a"=>"b","c"=>"d");
        $this->assertEquals($resultConfig, $result);
    }
    
    public function testMergeRegions()
    {
        // Arrange
        $path1 = "/foo";
        $path2 = "/bar";
        $region = "acme";
        $file = $region . ".php";
        $array1 = array("a" => "lorem", "b" => "ipsum");
        $array2 = array("b" => "dolor", "c" => "sit amet");
        $expected = array("a" => "lorem", "b" => "dolor", "c" => "sit amet");
        $resource1 = $this->mockFactory->getMockLocalFilesystemDirResource($path1);
        $resource1->shouldReceive('loadFile')->with($file, false)->andReturn($array1);
        $resource2 = $this->mockFactory->getMockLocalFilesystemDirResource($path2);
        $resource2->shouldReceive('loadFile')->with($file, false)->andReturn($array2);
    
        // Act
        $config = new Config();
        $config->appendResource($resource1);
        $config->appendResource($resource2);
        $arrayAdapter = new ArrayAdapter();
        $result = $arrayAdapter->load($config, $region);
    
        // Assert
        $this->assertEquals($expected, $result);
    }
    
    public function testMergeRegionsRecursive()
    {
        // Arrange
        $path1 = "/foo";
        $path2 = "/bar";
        $region = "acme";
        $file = $region . ".php";
        $array1 = array("a" => array("b" => "lorem", "c" => "ipsum"));
        $array2 = array("a" => array("c" => "dolor", "d" => "sit amet"));
        $expected = array("a" => array("b" => "lorem", "c" => "dolor", "d" => "sit amet"));
        $resource1 = $this->mockFactory->getMockLocalFilesystemDirResource($path1);
        $resource1->shouldReceive('loadFile')->with($file, false)->andReturn($array1);
        $resource2 = $this->mockFactory->getMockLocalFilesystemDirResource($path2);
        $resource2->shouldReceive('loadFile')->with($file, false)->andReturn($array2);
    
        // Act
        $config = new Config();
        $config->appendResource($resource1);
        $config->appendResource($resource2);
        $arrayAdapter = new ArrayAdapter();
        $result = $arrayAdapter->load($config, $region);
    
        // Assert
        $this->assertEquals($expected, $result);
    }
}
