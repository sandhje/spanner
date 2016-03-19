<?php
namespace Sandhje\Spanner\Resource\Strategy;

use Sandhje\Spanner\Filesystem\Filesystem;

/**
 *
 * @author Sandhje
 *        
 */
class LocalFilesystemFileStrategy extends LocalFilesystemBaseStrategy implements LocalFilesystemStrategyInterface
{
    /**
     * Filesystem wrapper class
     * 
     * @var Filesystem
     */
    protected $filesystem;
    
    public function __construct(Filesystem $filesystem = null)
    {
        $this->filesystem = (!$filesystem ? new Filesystem() : $filesystem);
    }
    
    /**
     * (non-PHPdoc)
     *
     * @see \Sandhje\Spanner\Resource\Strategy\LocalFilesystemStrategyInterface::loadFile()
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