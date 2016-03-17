<?php
namespace Sandhje\Spanner\Resource\Strategy;

use Sandhje\Spanner\Filesystem\Filesystem;

/**
 *
 * @author Sandhje
 *        
 */
class LocalFilesystemDirectoryStrategy extends LocalFilesystemBaseStrategy implements LocalFilesystemStrategyInterface
{
    /**
     * Filesystem wrapper class
     * 
     * @var Filesystem
     */
    protected $filesystem;
    
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = (!$filesystem ? new Filesystem() : $filesystem);        
    }
    
    /**
     * {@inheritDoc}
     * @see \Sandhje\Spanner\Resource\Strategy\LocalFilesystemStrategyInterface::loadFile()
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