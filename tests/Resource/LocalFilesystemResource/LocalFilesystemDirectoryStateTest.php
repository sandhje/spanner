<?php
namespace Sandhje\Spanner\Test\Resource\LocalFilesystemResource;

use Mockery;
use Sandhje\Spanner\Resource\LocalFilesystemResource\LocalFilesystemDirectoryState;

/**
 *
 * @author Sandhje
 *        
 */
class LocalFilesystemDirectoryStateTest extends \PHPUnit_Framework_TestCase
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
        $filesystemProxy = Mockery::mock('Sandhje\Spanner\Proxy\FilesystemProxy');
        $filesystemProxy->shouldReceive('isFile')->with($resourceFile)->andReturn(true);
        $filesystemProxy->shouldReceive('isReadable')->with($resourceFile)->andReturn(true);
        $filesystemProxy->shouldReceive('load')->with($resourceFile)->andReturn($fileContent);
        
        // Act
        $dirState = new LocalFilesystemDirectoryState($filesystemProxy);
        $result = $dirState->loadFile($resource, $file);
        
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
        $filesystemProxy = Mockery::mock('Sandhje\Spanner\Proxy\FilesystemProxy');
        $filesystemProxy->shouldReceive('isFile')->with($environmentResource)->andReturn(true);
        $filesystemProxy->shouldReceive('isReadable')->with($environmentResource)->andReturn(true);
        $filesystemProxy->shouldReceive('load')->with($environmentResource)->andReturn($fileContent);
        
        // Act
        $dirState = new LocalFilesystemDirectoryState($filesystemProxy);
        $result = $dirState->loadFile($resource, $file, $environment);
        
        // Assert
        $this->assertEquals($fileContent, $result);
    }
    
    public function testLoadUnmatchedFile()
    {
        // Arrange
        $resource = '/foo';
        $file = 'bar.php';
        $resourceFile = $resource . "/" . $file;
        $filesystemProxy = Mockery::mock('Sandhje\Spanner\Proxy\FilesystemProxy');
        $filesystemProxy->shouldReceive('isFile')->with($resourceFile)->andReturn(false);
        $filesystemProxy->shouldNotReceive('isReadable');
        $filesystemProxy->shouldNotReceive('load');
        
        // Act
        $dirState = new LocalFilesystemDirectoryState($filesystemProxy);
        $result = $dirState->loadFile($resource, $file);
        
        // Assert
        $this->assertFalse($result);
    }
}

?>