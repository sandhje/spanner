<?php
namespace Sandhje\Spanner\Resource\Strategy;

/**
 *
 * @author Sandhje
 *        
 */
interface LocalFilesystemStrategyInterface
{
    /**
     * Load a file from the filesystem resource
     * 
     * @param string $resource
     * @param string $file
     * @param string $environment
     * @return mixed File content
     */
    public function loadFile($resource, $file, $environment = false);
}

?>