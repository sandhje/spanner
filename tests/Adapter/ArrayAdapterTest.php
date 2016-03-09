<?php

namespace Sandhje\Spanner\Test\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\ArrayAdapter;
use Mockery;

class ArrayAdapterTest extends \PHPUnit_Framework_TestCase
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
        $file = $path . "/" . $region . ".php";
        $testConfig = array("a" => "b");
        $config = new Config();
        $config->appendPath($path);
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_file')->with($file)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($file)->andReturn(true);
        $filesystem->shouldReceive('load')->with($file)->andReturn($testConfig);
        
        // Act
        $arrayAdapter = new ArrayAdapter($filesystem);
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
        $envPath = $path . "/" . $env;
        $file = $path . "/" . $region . ".php";
        $envFile = $envPath . "/" . $region . ".php";
        $testConfig = array("a" => "b");
        $testEnvConfig = array("c" => "d");
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
        $arrayAdapter = new ArrayAdapter($filesystem);
        $result = $arrayAdapter->load($config, $region);
        
        // Assert
        $resultConfig = array("a"=>"b","c"=>"d");
        $this->assertEquals($resultConfig, $result);
    }
}
