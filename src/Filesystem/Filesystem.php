<?php
namespace Sandhje\Spanner\Filesystem;

/**
 * Filesystem wrapper
 *
 * @author Sandhje
 *        
 */
class Filesystem
{
    /**
     * is_file wrapper
     * 
     * @param string $filename
     */
    public function is_file($filename) 
    {
        return is_file($filename);
    }
    
    /**
     * is_readable wrapper
     * 
     * @param string $filename
     * @return boolean
     */
    public function is_readable($filename) 
    {
        return is_readable($filename);
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

?>