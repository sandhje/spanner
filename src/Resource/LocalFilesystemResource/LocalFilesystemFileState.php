<?php
namespace Sandhje\Spanner\Resource\LocalFilesystemResource;

/**
 *
 * @author Sandhje
 *        
 */
class LocalFilesystemFileState extends LocalFilesystemBaseState implements LocalFilesystemStateInterface
{
    /**
     * (non-PHPdoc)
     *
     * @see \Sandhje\Spanner\Resource\Strategy\LocalFilesystemStateInterface::loadFile()
     *
     */
    public function loadFile($resource, $file, $environment = false)
    {
        $loadResult = false;
        
        $resourcePathInfo = $this->filesystem->pathinfo($resource);
        
        if($file != $resourcePathInfo["basename"]) {
            return false;
        }
        
        if($environment) {
            $environmentFile = $resourcePathInfo["filename"] . "." . $environment . "." . $resourcePathInfo["extension"];
            $location = $this->mergePathParts($resourcePathInfo["dirname"], $environmentFile);
            $loadResult = $this->load($location);
        }
        
        if(!$loadResult) {
            $loadResult = $this->load($resource);
        }
        
        return $loadResult;
    }
}

?>