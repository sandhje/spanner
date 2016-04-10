<?php
namespace Sandhje\Spanner\Test\Resource\LocalFilesystemResource;

use Mockery;
use Sandhje\Spanner\Resource\LocalFilesystemResource\LocalFilesystemFileState;

/**
 *
 * @author Sandhje
 *        
 */
class LocalFilesystemFileStateTest extends \PHPUnit_Framework_TestCase
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
        $filesystemProxy = Mockery::mock('Sandhje\Spanner\Proxy\FilesystemProxy');
        $filesystemProxy->shouldReceive('pathinfo')->with($resource)->andReturn($resourcePathInfo);
        $filesystemProxy->shouldReceive('is_file')->with($resource)->andReturn(true);
        $filesystemProxy->shouldReceive('is_readable')->with($resource)->andReturn(true);
        $filesystemProxy->shouldReceive('load')->with($resource)->andReturn($fileContent);
        
        // Act
        $fileState = new LocalFilesystemFileState($filesystemProxy);
        $result = $fileState->loadFile($resource, $file);
        
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
        $filesystemProxy = Mockery::mock('Sandhje\Spanner\Proxy\FilesystemProxy');
        $filesystemProxy->shouldReceive('pathinfo')->with($resource)->andReturn($resourcePathInfo);
        $filesystemProxy->shouldReceive('is_file')->with($environmentResource)->andReturn(true);
        $filesystemProxy->shouldReceive('is_readable')->with($environmentResource)->andReturn(true);
        $filesystemProxy->shouldReceive('load')->with($environmentResource)->andReturn($fileContent);
        
        // Act
        $fileState = new LocalFilesystemFileState($filesystemProxy);
        $result = $fileState->loadFile($resource, $file, $environment);
        
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
        $filesystemProxy = Mockery::mock('Sandhje\Spanner\Proxy\FilesystemProxy');
        $filesystemProxy->shouldReceive('pathinfo')->with($resource)->andReturn($resourcePathInfo);
        $filesystemProxy->shouldNotReceive('is_file');
        $filesystemProxy->shouldNotReceive('is_readable');
        $filesystemProxy->shouldNotReceive('load');
        
        // Act
        $fileState = new LocalFilesystemFileState($filesystemProxy);
        $result = $fileState->loadFile($resource, $file);
        
        // Assert
        $this->assertFalse($result);
    }
}

?>