<?php
namespace Sandhje\Spanner\Test\Resource\Strategy;

use Mockery;
use Sandhje\Spanner\Resource\Strategy\LocalFilesystemDirectoryStrategy;

/**
 *
 * @author Sandhje
 *        
 */
class LocalFilesystemDirectoryStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testLoadFileWithoutEnvironment()
    {
        // Arrange
        $resource = '/foo';
        $file = 'bar.php';
        $resourceFile = $resource . "/" . $file;
        $fileContent = array("a" => "b");
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_file')->with($resourceFile)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($resourceFile)->andReturn(true);
        $filesystem->shouldReceive('load')->with($resourceFile)->andReturn($fileContent);
        
        // Act
        $fileStrategy = new LocalFilesystemDirectoryStrategy($filesystem);
        $result = $fileStrategy->loadFile($resource, $file);
        
        // Assert
        $this->assertEquals($fileContent, $result);
    }
    
    public function testLoadFileWithEnvironment()
    {
        // Arrange
        $resource = '/foo';
        $file = 'bar.php';
        $fileContent = array("a" => "b");
        $environment = "acme";
        $environmentResource = "/foo/acme/bar.php";
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_file')->with($environmentResource)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($environmentResource)->andReturn(true);
        $filesystem->shouldReceive('load')->with($environmentResource)->andReturn($fileContent);
        
        // Act
        $fileStrategy = new LocalFilesystemDirectoryStrategy($filesystem);
        $result = $fileStrategy->loadFile($resource, $file, $environment);
        
        // Assert
        $this->assertEquals($fileContent, $result);
    }
    
    public function testLoadUnmatchedFile()
    {
        // Arrange
        $resource = '/foo';
        $file = 'bar.php';
        $resourceFile = $resource . "/" . $file;
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_file')->with($resourceFile)->andReturn(false);
        $filesystem->shouldNotReceive('is_readable');
        $filesystem->shouldNotReceive('load');
        
        // Act
        $fileStrategy = new LocalFilesystemDirectoryStrategy($filesystem);
        $result = $fileStrategy->loadFile($resource, $file);
        
        // Assert
        $this->assertFalse($result);
    }
}

?>