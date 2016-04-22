<?php
namespace Sandhje\Spanner\Resource\LocalFilesystemResource;

/**
 *
 * @author Sandhje
 *        
 */
interface LocalFilesystemStateInterface
{
    /**
     * Load a file from the filesystem resource
     * 
     * @param string $resource
     * @param string $file
     * @param string|array $environment
     * @return mixed File content
     */
    public function loadFile($resource, $file, $environment = false);
}
