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
    public function is_file($filename) 
    {
        return is_file($filename);
    }
    
    public function is_readable($filename) 
    {
        return is_readable($filename);
    }
    
    public function load($filename) 
    {
        return (include $filename);
    }
}

?>