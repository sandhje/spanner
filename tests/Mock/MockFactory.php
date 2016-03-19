<?php
namespace Sandhje\Spanner\Test\Mock;

use Mockery;

/**
 *
 * @author Sandhje
 *        
 */
class MockFactory
{
    public function getMockLocalFilesystemDirResource($path)
    {
        $filesystem = Mockery::mock('Sandhje\Spanner\Filesystem\Filesystem');
        $filesystem->shouldReceive('is_readable')->with($path)->andReturn(true);
        $filesystem->shouldReceive('is_file')->with($path)->andReturn(false);
        return Mockery::mock('Sandhje\Spanner\Resource\LocalFilesystemResource', array($path, null, $filesystem));
    }
}

?>