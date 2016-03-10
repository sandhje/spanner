<?php

namespace Sandhje\Spanner\Test\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\YamlAdapter;
use Mockery;
use Symfony\Component\Yaml\Yaml;

class YmlAdapterTest extends \PHPUnit_Framework_TestCase
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
        $file = $path . "/" . $region . ".yml";
        $testConfig = Yaml::dump(array("a" => "b"));
        $config = new Config();
        $config->appendPath($path);
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_file')->with($file)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($file)->andReturn(true);
        $filesystem->shouldReceive('load')->with($file)->andReturn($testConfig);
        
        // Act
        $yamlAdapter = new YamlAdapter($filesystem);
        $result = $yamlAdapter->load($config, $region);
        
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
        $file = $path . "/" . $region . ".yml";
        $envFile = $envPath . "/" . $region . ".yml";
        $testConfig = Yaml::dump(array("a" => "b"));
        $testEnvConfig = Yaml::dump(array("c" => "d"));
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
        $yamlAdapter = new YamlAdapter($filesystem);
        $result = $yamlAdapter->load($config, $region);
        
        // Assert
        $this->assertEquals(array("a"=>"b","c"=>"d"), $result);
    }
    
    public function testMergeRegions()
    {
        // Arrange
        $path1 = "/foo";
        $path2 = "/bar";
        $region = "acme";
        $file1 = $path1 . "/" . $region . ".yml";
        $file2 = $path2 . "/" . $region . ".yml";
        $array1 = Yaml::dump(array("a" => "lorem", "b" => "ipsum"));
        $array2 = Yaml::dump(array("b" => "dolor", "c" => "sit amet"));
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
        $yamlAdapter = new YamlAdapter($filesystem);
        $result = $yamlAdapter->load($config, $region);
    
        // Assert
        $this->assertEquals(array("a" => "lorem", "b" => "dolor", "c" => "sit amet"), $result);
    }
    
    public function testMergeRegionsRecursive()
    {
        // Arrange
        $path1 = "/foo";
        $path2 = "/bar";
        $region = "acme";
        $file1 = $path1 . "/" . $region . ".yml";
        $file2 = $path2 . "/" . $region . ".yml";
        $array1 = Yaml::dump(array("a" => array("b" => "lorem", "c" => "ipsum")));
        $array2 = Yaml::dump(array("a" => array("c" => "dolor", "d" => "sit amet")));
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
        $yamlAdapter = new YamlAdapter($filesystem);
        $result = $yamlAdapter->load($config, $region);
    
        // Assert
        $this->assertEquals(array("a" => array("b" => "lorem", "c" => "dolor", "d" => "sit amet")), $result);
    }
}
