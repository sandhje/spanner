<?php
namespace Sandhje\Spanner\Test\Resource\Strategy;

use Mockery;
use Sandhje\Spanner\Resource\Strategy\LocalFilesystemFileStrategy;

/**
 *
 * @author Sandhje
 *        
 */
class LocalFilesystemFileStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testLoadFileWithoutEnvironment()
    {
        // Arrange
        $resource = '/foo/bar.php';
        $resourcePathInfo = array(
            'dirname' => '/foo',
            'basename' => 'bar.php',
            'extension' => 'php',
            'filename' => 'bar'
        );
        $file = 'bar.php';
        $fileContent = array("a" => "b");
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('pathinfo')->with($resource)->andReturn($resourcePathInfo);
        $filesystem->shouldReceive('is_file')->with($resource)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($resource)->andReturn(true);
        $filesystem->shouldReceive('load')->with($resource)->andReturn($fileContent);
        
        // Act
        $fileStrategy = new LocalFilesystemFileStrategy($filesystem);
        $result = $fileStrategy->loadFile($resource, $file);
        
        // Assert
        $this->assertEquals($fileContent, $result);
    }
    
    public function testLoadFileWithEnvironment()
    {
        // Arrange
        $resource = '/foo/bar.php';
        $resourcePathInfo = array(
            'dirname' => '/foo',
            'basename' => 'bar.php',
            'extension' => 'php',
            'filename' => 'bar'
        );
        $file = 'bar.php';
        $fileContent = array("a" => "b");
        $environment = "acme";
        $environmentResource = "/foo/bar.acme.php";
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('pathinfo')->with($resource)->andReturn($resourcePathInfo);
        $filesystem->shouldReceive('is_file')->with($environmentResource)->andReturn(true);
        $filesystem->shouldReceive('is_readable')->with($environmentResource)->andReturn(true);
        $filesystem->shouldReceive('load')->with($environmentResource)->andReturn($fileContent);
        
        // Act
        $fileStrategy = new LocalFilesystemFileStrategy($filesystem);
        $result = $fileStrategy->loadFile($resource, $file, $environment);
        
        // Assert
        $this->assertEquals($fileContent, $result);
    }
    
    public function testLoadUnmatchedFile()
    {
        // Arrange
        $resource = '/foo/bar.php';
        $resourcePathInfo = array(
            'dirname' => '/foo',
            'basename' => 'bar.php',
            'extension' => 'php',
            'filename' => 'bar'
        );
        $file = 'foo.php';
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('pathinfo')->with($resource)->andReturn($resourcePathInfo);
        $filesystem->shouldNotReceive('is_file');
        $filesystem->shouldNotReceive('is_readable');
        $filesystem->shouldNotReceive('load');
        
        // Act
        $fileStrategy = new LocalFilesystemFileStrategy($filesystem);
        $result = $fileStrategy->loadFile($resource, $file);
        
        // Assert
        $this->assertFalse($result);
    }
}

?>