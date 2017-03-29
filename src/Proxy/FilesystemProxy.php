<?php
namespace OpenSourcerers\Spanner\Proxy;

/**
 * Filesystem proxy
 *
 * @author Sandhje
 *        
 */
class FilesystemProxy
{
    /**
     * Equals PATHINFO_DIRNAME | PATHINFO_BASENAME | PATHINFO_EXTENSION | PATHINFO_FILENAME
     * 
     * @var int
     */
    const PATHINFO_ALL = 15;
    
    /**
     * is_file wrapper
     * 
     * @param string $filename
     */
    public function isFile($filename) 
    {
        return is_file($filename);
    }
    
    /**
     * is_dir wrapper
     *
     * @param string $dir
     */
    public function isDir($directory)
    {
        return is_dir($directory);
    }
    
    /**
     * is_readable wrapper
     * 
     * @param string $filename
     * @return boolean
     */
    public function isReadable($filename) 
    {
        return is_readable($filename);
    }
    
    /**
     * pathinfo wrapper
     *
     * @param string $path
     * @param int $options
     * @return mixed
     */
    public function pathinfo($path, $options = self::PATHINFO_ALL)
    {
        return pathinfo($path, $options);
    }
    
    /**
     * Load file from filesystem
     * 
     * @param string $filename
     * @return string
     */
    public function load($filename) 
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        
        if($ext === "php") {
            return (include $filename);
        } else {
            return file_get_contents($filename);            
        }
        
    }
}
