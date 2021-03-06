<?php
namespace OpenSourcerers\Spanner\Resource\LocalFilesystemResource;

use OpenSourcerers\Spanner\Proxy\FilesystemProxy;
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
        foreach ($argList as $i => $arg) {
            $result = ($i == 0) ? $arg : rtrim($result, "/") . "/" . ltrim($arg, "/");
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
        if($this->filesystemProxy->isFile($location) && $this->filesystemProxy->isReadable($location)) {
            return $this->filesystemProxy->load($location);
        }
    
        return false;
    }
}
