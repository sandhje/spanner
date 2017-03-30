<?php
namespace OpenSourcerers\Spanner\Resource\LocalFilesystemResource;

/**
 *
 * @author Sandhje
 *        
 */
class LocalFilesystemDirectoryState extends LocalFilesystemBaseState implements LocalFilesystemStateInterface
{
    /**
     * {@inheritDoc}
     * @see \OpenSourcerers\Spanner\Resource\Strategy\LocalFilesystemStateInterface::loadFile()
     */
    public function loadFile($resource, $file, $environment = false)
    {
        $loadResult = false;
        
        if($environment) {
            $location = $resource;
            
            if(!is_array($environment))
                $environment = array($environment);
            
            foreach($environment as $segment) {
                $location = $this->mergePathParts($location, $segment);
            }
            
            $location = $this->mergePathParts($location, $file);
            $loadResult = $this->load($location);
        } else {          
            $location = $this->mergePathParts($resource, $file);
            $loadResult = $this->load($location);
        }
        
        return $loadResult;
    }
}
