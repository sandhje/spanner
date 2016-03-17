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
        $resourceStrategy = Mockery::mock('Sandhje\Spanner\Resource\Strategy\LocalFilesystemStrategyInterface');
        $resourceStrategy->shouldReceive("loadFile")->with($resource, $file, $environment)->andReturn($fileContent);
        
        // Act
        $localFilesystemResource = new LocalFilesystemResource($resource, $resourceStrategy, $filesystem);
        $result = $localFilesystemResource->loadFile($file, $environment);
        
        // Assert
        $this->assertEquals($fileContent, $result);
    }
}

?>