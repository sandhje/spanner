<?php

namespace Sandhje\Spanner\Test\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\XmlAdapter;
use Mockery;
use Sandhje\Spanner\Test\Mock\MockFactory;

class XmlAdapterTest extends \PHPUnit_Framework_TestCase
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
        $file = $region . ".xml";
        $testConfig = json_encode(array("a" => "b"));
        $testConfig = "<bar><a>b</a></bar>";
        $resource = $this->mockFactory->getMockLocalFilesystemDirResource($path);
        $resource->shouldReceive('loadFile')->with($file, false)->andReturn($testConfig);
        
        // Act
        $config = new Config();
        $config->appendResource($resource);
        $xmlAdapter = new XmlAdapter();
        $result = $xmlAdapter->load($config, $region);
        
        // Assert
        $this->assertEquals(array("a" => "b"), $result);
    }
    
    public function testLoadRegionWithEnvironment()
    {
        // Arrange
        $path = "/foo";
        $region = "bar";
        $env = "test";
        $file = $region . ".xml";
        $testConfig = "<bar><a>b</a></bar>";
        $testEnvConfig = "<bar><c>d</c></bar>";
        $resource = $this->mockFactory->getMockLocalFilesystemDirResource($path);
        $resource->shouldReceive('loadFile')->with($file, false)->andReturn($testConfig);
        $resource->shouldReceive('loadFile')->with($file, $env)->andReturn($testEnvConfig);
        
        // Act
        $config = new Config();
        $config->appendResource($resource);
        $config->setEnvironment($env);
        $xmlAdapter = new XmlAdapter();
        $result = $xmlAdapter->load($config, $region);
        
        // Assert
        $this->assertEquals(array("a"=>"b","c"=>"d"), $result);
    }
    
    public function testMergeRegions()
    {
        // Arrange
        $path1 = "/foo";
        $path2 = "/bar";
        $region = "acme";
        $file = $region . ".xml";
        $array1 = "<acme><a>lorem</a><b>ipsum</b></acme>";
        $array2 = "<acme><b>dolor</b><c>sit amet</c></acme>";
        $resource1 = $this->mockFactory->getMockLocalFilesystemDirResource($path1);
        $resource1->shouldReceive('loadFile')->with($file, false)->andReturn($array1);
        $resource2 = $this->mockFactory->getMockLocalFilesystemDirResource($path2);
        $resource2->shouldReceive('loadFile')->with($file, false)->andReturn($array2);
    
        // Act
        $config = new Config();
        $config->appendResource($resource1);
        $config->appendResource($resource2);
        $xmlAdapter = new XmlAdapter();
        $result = $xmlAdapter->load($config, $region);
    
        // Assert
        $this->assertEquals(array("a" => "lorem", "b" => "dolor", "c" => "sit amet"), $result);
    }
    
    public function testMergeRegionsRecursive()
    {
        // Arrange
        $path1 = "/foo";
        $path2 = "/bar";
        $region = "acme";
        $file = $region . ".xml";
        $array1 = "<acme><a><b>lorem</b><c>ipsum</c></a></acme>";
        $array2 = "<acme><a><c>dolor</c><d>sit amet</d></a></acme>";
        $resource1 = $this->mockFactory->getMockLocalFilesystemDirResource($path1);
        $resource1->shouldReceive('loadFile')->with($file, false)->andReturn($array1);
        $resource2 = $this->mockFactory->getMockLocalFilesystemDirResource($path2);
        $resource2->shouldReceive('loadFile')->with($file, false)->andReturn($array2);
    
        // Act
        $config = new Config();
        $config->appendResource($resource1);
        $config->appendResource($resource2);
        $xmlAdapter = new XmlAdapter();
        $result = $xmlAdapter->load($config, $region);
    
        // Assert
        $this->assertEquals(array("a" => array("b" => "lorem", "c" => "dolor", "d" => "sit amet")), $result);
    }
}
