<?php

namespace Sandhje\Spanner\Test\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\XmlAdapter;
use Mockery;

class XmlAdapterTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testLoadRegion()
    {
        // Arrange
        $path = "/foo";
        $region = "bar";
        $file = $path . "/" . $region . ".xml";
        $testConfig = json_encode(array("a" => "b"));
        $testConfig = "<bar><a>b</a></bar>";
        $config = new Config();
        $config->appendPath($path);
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_file')->with($file)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($file)->andReturn(true);
        $filesystem->shouldReceive('load')->with($file)->andReturn($testConfig);
        
        // Act
        $xmlAdapter = new XmlAdapter($filesystem);
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
        $envPath = $path . "/" . $env;
        $file = $path . "/" . $region . ".xml";
        $envFile = $envPath . "/" . $region . ".xml";
        $testConfig = "<bar><a>b</a></bar>";
        $testEnvConfig = "<bar><c>d</c></bar>";
        $config = new Config();
        $config->appendPath($path);
        $config->setEnvironment($env);
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_file')->with($file)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($file)->andReturn(true);
        $filesystem->shouldReceive('load')->with($file)->andReturn($testConfig);
        $filesystem->shouldReceive('is_file')->with($envFile)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($envFile)->andReturn(true);
        $filesystem->shouldReceive('load')->with($envFile)->andReturn($testEnvConfig);
        
        // Act
        $xmlAdapter = new XmlAdapter($filesystem);
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
        $file1 = $path1 . "/" . $region . ".xml";
        $file2 = $path2 . "/" . $region . ".xml";
        $array1 = "<acme><a>lorem</a><b>ipsum</b></acme>";
        $array2 = "<acme><b>dolor</b><c>sit amet</c></acme>";
        $config = new Config();
        $config->appendPath($path1);
        $config->appendPath($path2);
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_file')->with($file1)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($file1)->andReturn(true);
        $filesystem->shouldReceive('load')->with($file1)->andReturn($array1);
        $filesystem->shouldReceive('is_file')->with($file2)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($file2)->andReturn(true);
        $filesystem->shouldReceive('load')->with($file2)->andReturn($array2);
    
        // Act
        $xmlAdapter = new XmlAdapter($filesystem);
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
        $file1 = $path1 . "/" . $region . ".xml";
        $file2 = $path2 . "/" . $region . ".xml";
        $array1 = "<acme><a><b>lorem</b><c>ipsum</c></a></acme>";
        $array2 = "<acme><a><c>dolor</c><d>sit amet</d></a></acme>";
        $config = new Config();
        $config->appendPath($path1);
        $config->appendPath($path2);
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_file')->with($file1)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($file1)->andReturn(true);
        $filesystem->shouldReceive('load')->with($file1)->andReturn($array1);
        $filesystem->shouldReceive('is_file')->with($file2)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($file2)->andReturn(true);
        $filesystem->shouldReceive('load')->with($file2)->andReturn($array2);
    
        // Act
        $xmlAdapter = new XmlAdapter($filesystem);
        $result = $xmlAdapter->load($config, $region);
    
        // Assert
        $this->assertEquals(array("a" => array("b" => "lorem", "c" => "dolor", "d" => "sit amet")), $result);
    }
}
