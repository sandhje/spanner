<?php
namespace Sandhje\Spanner\Resource\LocalFilesystemResource;

/**
 *
 * @author Sandhje
 *        
 */
class LocalFilesystemDirectoryState extends LocalFilesystemBaseState implements LocalFilesystemStateInterface
{
    /**
     * {@inheritDoc}
     * @see \Sandhje\Spanner\Resource\Strategy\LocalFilesystemStateInterface::loadFile()
     */
    public function loadFile($resource, $file, $environment = false)
    {
        $loadResult = false;
        
        if($environment) {
            $location = $this->mergePathParts($resource, $environment, $file);
            $loadResult = $this->load($location);
        }
        
        if(!$loadResult) {            
            $location = $this->mergePathParts($resource, $file);
            $loadResult = $this->load($location);
        }
        
        return $loadResult;
    }
}

?>