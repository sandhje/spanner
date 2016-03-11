<?php

namespace Sandhje\Spanner\Test\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\IniAdapter;
use Mockery;

class IniAdapterTest extends \PHPUnit_Framework_TestCase
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
        $file = $path . "/" . $region . ".ini";
        $testConfig = 'a=b';
        $config = new Config();
        $config->appendPath($path);
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_file')->with($file)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($file)->andReturn(true);
        $filesystem->shouldReceive('load')->with($file)->andReturn($testConfig);
        
        // Act
        $iniAdapter = new IniAdapter($filesystem);
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
        $envPath = $path . "/" . $env;
        $file = $path . "/" . $region . ".ini";
        $envFile = $envPath . "/" . $region . ".ini";
        $testConfig = 'a=b';
        $testEnvConfig = 'c=d';
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
        $iniAdapter = new IniAdapter($filesystem);
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
        $file1 = $path1 . "/" . $region . ".ini";
        $file2 = $path2 . "/" . $region . ".ini";
        $array1 = '
            a=lorem
            b=ipsum
        ';
        $array2 = '
            b=dolor
            c=sit amet
        ';
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
        $iniAdapter = new IniAdapter($filesystem);
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
        $file1 = $path1 . "/" . $region . ".ini";
        $file2 = $path2 . "/" . $region . ".ini";
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
        $iniAdapter = new IniAdapter($filesystem);
        $result = $iniAdapter->load($config, $region);
    
        // Assert
        $this->assertEquals(array("a" => array("b" => "lorem", "c" => "dolor", "d" => "sit amet")), $result);
    }
}
