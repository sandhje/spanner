<?php

namespace Sandhje\Spanner\Test\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\JsonAdapter;
use Mockery;

class JsonAdapterTest extends \PHPUnit_Framework_TestCase
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
        $file = $path . "/" . $region . ".json";
        $testConfig = json_encode(array("a" => "b"));
        $config = new Config();
        $config->appendPath($path);
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_file')->with($file)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($file)->andReturn(true);
        $filesystem->shouldReceive('load')->with($file)->andReturn($testConfig);
        
        // Act
        $jsonAdapter = new JsonAdapter($filesystem);
        $result = $jsonAdapter->load($config, $region);
        
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
        $file = $path . "/" . $region . ".json";
        $envFile = $envPath . "/" . $region . ".json";
        $testConfig = json_encode(array("a" => "b"));
        $testEnvConfig = json_encode(array("c" => "d"));
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
        $jsonAdapter = new JsonAdapter($filesystem);
        $result = $jsonAdapter->load($config, $region);
        
        // Assert
        $this->assertEquals(array("a"=>"b","c"=>"d"), $result);
    }
    
    public function testMergeRegions()
    {
        // Arrange
        $path1 = "/foo";
        $path2 = "/bar";
        $region = "acme";
        $file1 = $path1 . "/" . $region . ".json";
        $file2 = $path2 . "/" . $region . ".json";
        $array1 = json_encode(array("a" => "lorem", "b" => "ipsum"));
        $array2 = json_encode(array("b" => "dolor", "c" => "sit amet"));
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
        $jsonAdapter = new JsonAdapter($filesystem);
        $result = $jsonAdapter->load($config, $region);
    
        // Assert
        $this->assertEquals(array("a" => "lorem", "b" => "dolor", "c" => "sit amet"), $result);
    }
    
    public function testMergeRegionsRecursive()
    {
        // Arrange
        $path1 = "/foo";
        $path2 = "/bar";
        $region = "acme";
        $file1 = $path1 . "/" . $region . ".json";
        $file2 = $path2 . "/" . $region . ".json";
        $array1 = json_encode(array("a" => array("b" => "lorem", "c" => "ipsum")));
        $array2 = json_encode(array("a" => array("c" => "dolor", "d" => "sit amet")));
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
        $jsonAdapter = new JsonAdapter($filesystem);
        $result = $jsonAdapter->load($config, $region);
    
        // Assert
        $this->assertEquals(array("a" => array("b" => "lorem", "c" => "dolor", "d" => "sit amet")), $result);
    }
}
