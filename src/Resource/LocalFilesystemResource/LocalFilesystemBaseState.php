<?php
namespace Sandhje\Spanner\Resource\LocalFilesystemResource;

use Sandhje\Spanner\Proxy\FilesystemProxy;
/**
 *
 * @author Sandhje
 *        
 */
abstract class LocalFilesystemBaseState
{
    /**
     * @var FilesystemProxy
     */
    protected $filesystemProxy;
    
    public function __construct(FilesystemProxy $filesystemProxy)
    {
        $this->filesystemProxy = $filesystemProxy;
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
        if($this->filesystemProxy->is_file($location) && $this->filesystemProxy->is_readable($location)) {
            return $this->filesystemProxy->load($location);
        }
    
        return false;
    }
}

?>