<?php
namespace Sandhje\Spanner\Resource\LocalFilesystemResource;

use Sandhje\Spanner\Filesystem\Filesystem;
/**
 *
 * @author Sandhje
 *        
 */
abstract class LocalFilesystemBaseState
{
    /**
     * @var Filesystem
     */
    protected $filesystem;
    
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }
    
    /**
     * Combine the passed path parts create a complete path string
     * 
     * @return string
     */
    protected function mergePathParts()
    {
        $result = "";
        
        $argList = func_get_args();
        for ($i = 0; $i < func_num_args(); $i++) {
            $result = ($i == 0) ? $argList[$i] : rtrim($result, "/") . "/" . ltrim($argList[$i], "/");
        }
        
        return $result;
    }
    
    /**
     * Load a file from the filesystem
     * 
     * @param unknown $location
     */
    protected function load($location)
    {
        if($this->filesystem->is_file($location) && $this->filesystem->is_readable($location)) {
            return $this->filesystem->load($location);
        }
    
        return false;
    }
}

?>