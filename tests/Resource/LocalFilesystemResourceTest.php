<?php
namespace Sandhje\Spanner\Test\Resource;

use Sandhje\Spanner\Resource\LocalFilesystemResource;
use Mockery;
use Sandhje\Spanner\Resource\Strategy\ArrayStrategy;
use Sandhje\Spanner\Resource\Strategy\YamlStrategy;

/**
 *
 * @author Sandhje
 *        
 */
class LocalFilesystemResourceTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }
    
    public function testLoadFile()
    {
        // Arrange
        $resource = "/foo";
        $region = "bar";
        $file = [$region . ".php"];
        $environment = "acme";
        $fileContent = array("a" => "b");
        $filesystemProxy = Mockery::mock('Sandhje\Spanner\Proxy\FilesystemProxy');
        $filesystemProxy->shouldReceive('isReadable')->with($resource)->andReturn(true);
        $resourceState = Mockery::mock('Sandhje\Spanner\Resource\LocalFilesystemResource\LocalFilesystemStateInterface');
        $resourceState->shouldReceive("loadFile")->with($resource, $file[0], $environment)->andReturn($fileContent);
        
        // Act
        $localFilesystemResource = new LocalFilesystemResource($resource, new ArrayStrategy());
        $localFilesystemResource->setFilesystemProxy($filesystemProxy);
        $localFilesystemResource->setFileState($resourceState);
        $result = $localFilesystemResource->load($region, $environment);
        
        // Assert
        $this->assertEquals($fileContent, $result);
    }
    
    public function testLoadFileTwoExtensions()
    {
        // Test LocalFilesystemResource lines 152 and 159
        // Arrange
        $resource = "/foo";
        $region = "bar";
        $file = [$region . ".yml", $region . ".yaml"];
        $environment = "acme";
        $fileContent = "a: b";
        $resultFileContent = array("a" => "b");
        $filesystemProxy = Mockery::mock('Sandhje\Spanner\Proxy\FilesystemProxy');
        $filesystemProxy->shouldReceive('isReadable')->with($resource)->andReturn(true);
        $resourceState = Mockery::mock('Sandhje\Spanner\Resource\LocalFilesystemResource\LocalFilesystemStateInterface');
        $resourceState->shouldReceive("loadFile")->with($resource, $file[0], $environment)->andReturn(false);
        $resourceState->shouldReceive("loadFile")->with($resource, $file[1], $environment)->andReturn($fileContent);
        
        // Act
        $localFilesystemResource = new LocalFilesystemResource($resource, new YamlStrategy());
        $localFilesystemResource->setFilesystemProxy($filesystemProxy);
        $localFilesystemResource->setFileState($resourceState);
        $result = $localFilesystemResource->load($region, $environment);
        
        // Assert
        $this->assertEquals($resultFileContent, $result);
    }
    
    public function testUnreadableResource()
    {
        $this->setExpectedException('\Exception');
        
        // Arrange
        $resource = "/foo";
        $file = "bar.php";
        $environment = "acme";
        $filesystemProxy = Mockery::mock('Sandhje\Spanner\Proxy\FilesystemProxy');
        $filesystemProxy->shouldReceive('isReadable')->with($resource)->andReturn(false);
        
        // Act
        $localFilesystemResource = new LocalFilesystemResource($resource, new ArrayStrategy());
        $localFilesystemResource->setFilesystemProxy($filesystemProxy);
        $localFilesystemResource->load($file, $environment);
    }
    
    public function testGetSetFilesystemProxy()
    {
        // Arrange
        $resource = "/foo";
        
        // Act
        $localFilesystemResource = new LocalFilesystemResource($resource, new ArrayStrategy());
        $filesystemProxy = $localFilesystemResource->getFilesystemProxy();
        
        // Assert
        $this->assertInstanceOf('\Sandhje\Spanner\Proxy\FilesystemProxy', $filesystemProxy);
    }
    
    public function testGetSetFileState()
    {
        // Arrange
        $resource = "/foo.php";
        $filesystemProxy = Mockery::mock('\Sandhje\Spanner\Proxy\FilesystemProxy');
        $filesystemProxy->shouldReceive('isFile')->with('/foo.php')->andReturn(true);
    
        // Act
        $localFilesystemResource = new LocalFilesystemResource($resource, new ArrayStrategy());
        $localFilesystemResource->setFilesystemProxy($filesystemProxy);
        $state = $localFilesystemResource->getState();
    
        // Assert
        $this->assertInstanceOf('\Sandhje\Spanner\Resource\LocalFilesystemResource\LocalFilesystemFileState', $state);
    }
    
    public function testGetSetDirectoryState()
    {
        // Arrange
        $resource = "/foo";
        $filesystemProxy = Mockery::mock('\Sandhje\Spanner\Proxy\FilesystemProxy');
        $filesystemProxy->shouldReceive('isFile')->with('/foo')->andReturn(false);
    
        // Act
        $localFilesystemResource = new LocalFilesystemResource($resource, new ArrayStrategy());
        $localFilesystemResource->setFilesystemProxy($filesystemProxy);
        $state = $localFilesystemResource->getState();
    
        // Assert
        $this->assertInstanceOf('\Sandhje\Spanner\Resource\LocalFilesystemResource\LocalFilesystemDirectoryState', $state);
    }
}

?>