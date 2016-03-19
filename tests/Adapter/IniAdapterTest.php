<?php

namespace Sandhje\Spanner\Test\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\IniAdapter;
use Mockery;
use Sandhje\Spanner\Test\Mock\MockFactory;

class IniAdapterTest extends \PHPUnit_Framework_TestCase
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
        $file = $region . ".ini";
        $testConfig = 'a=b';
        $resource = $this->mockFactory->getMockLocalFilesystemDirResource($path);
        $resource->shouldReceive('load')->with($file, false)->andReturn($testConfig);
        
        // Act
        $config = new Config();
        $config->appendResource($resource);
        $iniAdapter = new IniAdapter();
        $result = $iniAdapter->load($config, $region);
        
        // Assert
        $this->assertEquals(array("a" => "b"), $result);
    }
    
    public function testLoadRegionWithEnvironment()
    {
        // Arrange
        $path = "/foo";
        $region = "bar";
        $env = "test";
        $file = $region . ".ini";
        $testConfig = 'a=b';
        $testEnvConfig = 'c=d';
        $resource = $this->mockFactory->getMockLocalFilesystemDirResource($path);
        $resource->shouldReceive('load')->with($file, false)->andReturn($testConfig);
        $resource->shouldReceive('load')->with($file, $env)->andReturn($testEnvConfig);
        
        // Act
        $config = new Config();
        $config->appendResource($resource);
        $config->setEnvironment($env);
        $iniAdapter = new IniAdapter();
        $result = $iniAdapter->load($config, $region);
        
        // Assert
        $this->assertEquals(array("a"=>"b","c"=>"d"), $result);
    }
    
    public function testMergeRegions()
    {
        // Arrange
        $path1 = "/foo";
        $path2 = "/bar";
        $region = "acme";
        $file = $region . ".ini";
        $array1 = '
            a=lorem
            b=ipsum
        ';
        $array2 = '
            b=dolor
            c=sit amet
        ';
        $resource1 = $this->mockFactory->getMockLocalFilesystemDirResource($path1);
        $resource1->shouldReceive('load')->with($file, false)->andReturn($array1);
        $resource2 = $this->mockFactory->getMockLocalFilesystemDirResource($path2);
        $resource2->shouldReceive('load')->with($file, false)->andReturn($array2);
    
        // Act
        $config = new Config();
        $config->appendResource($resource1);
        $config->appendResource($resource2);
        $iniAdapter = new IniAdapter();
        $result = $iniAdapter->load($config, $region);
    
        // Assert
        $this->assertEquals(array("a" => "lorem", "b" => "dolor", "c" => "sit amet"), $result);
    }
    
    public function testMergeRegionsRecursive()
    {
        // Arrange
        $path1 = "/foo";
        $path2 = "/bar";
        $region = "acme";
        $file = $region . ".ini";
        $array1 = '
[a]
b=lorem
c=ipsum
        ';
        $array2 = '
[a]
c=dolor
d=sit amet
        '; 
        $resource1 = $this->mockFactory->getMockLocalFilesystemDirResource($path1);
        $resource1->shouldReceive('load')->with($file, false)->andReturn($array1);
        $resource2 = $this->mockFactory->getMockLocalFilesystemDirResource($path2);
        $resource2->shouldReceive('load')->with($file, false)->andReturn($array2);
    
        // Act
        $config = new Config();
        $config->appendResource($resource1);
        $config->appendResource($resource2);
        $iniAdapter = new IniAdapter();
        $result = $iniAdapter->load($config, $region);
    
        // Assert
        $this->assertEquals(array("a" => array("b" => "lorem", "c" => "dolor", "d" => "sit amet")), $result);
    }
}
