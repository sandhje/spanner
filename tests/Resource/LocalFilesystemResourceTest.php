<?php
namespace Sandhje\Spanner\Test\Resource;

use Sandhje\Spanner\Resource\LocalFilesystemResource;
use Mockery;

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
        $file = "bar.php";
        $environment = "acme";
        $fileContent = array("a" => "b");
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_readable')->with($resource)->andReturn(true);
        $resourceState = Mockery::mock('Sandhje\Spanner\Resource\LocalFilesystemResource\LocalFilesystemStateInterface');
        $resourceState->shouldReceive("loadFile")->with($resource, $file, $environment)->andReturn($fileContent);
        
        // Act
        $localFilesystemResource = new LocalFilesystemResource($resource);
        $localFilesystemResource->setFilesystem($filesystem);
        $localFilesystemResource->setState($resourceState);
        $result = $localFilesystemResource->load($file, $environment);
        
        // Assert
        $this->assertEquals($fileContent, $result);
    }
    
    public function testUnreadableResource()
    {
        $this->setExpectedException('\Exception');
        
        // Arrange
        $resource = "/foo";
        $file = "bar.php";
        $environment = "acme";
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_readable')->with($resource)->andReturn(false);
        
        // Act
        $localFilesystemResource = new LocalFilesystemResource($resource);
        $localFilesystemResource->setFilesystem($filesystem);
        $result = $localFilesystemResource->load($file, $environment);
    }
}

?>