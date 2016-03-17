<?php
namespace Sandhje\Spanner\Resource;

/**
 *
 * @author Sandhje
 *        
 */
interface FilesystemResourceInterface
{
    /**
     * Try to load a file from the resource
     *
     * @param string $file
     * @param string $environment
     * @return string|bool
     */
    public function loadFile($file, $environment = false);
}

?>