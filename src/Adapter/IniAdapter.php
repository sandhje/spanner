<?php

namespace Sandhje\Spanner\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\ArrayAdapter;
use Sandhje\Spanner\Resource\FilesystemResourceInterface;

class IniAdapter extends ArrayAdapter
{
    /**
     * Load the passed file and return its contents
     * 
     * @param Sandhje\Spanner\Resource\FilesystemResourceInterface $resource
     * @param string $file
     * @param string $environment
     * @return array
     */
    protected function loadFile(FilesystemResourceInterface $resource, $file, $environment = false) 
    {
        $config = parent::loadFile($resource, $file, $environment);
        
        $config = parse_ini_string($config, true);
        
        if(!$config) { 
            throw new \Exception("Invalid configuration file.");
        }
        
        return $config;
    }
    
    protected function getFileName($region)
    {
        return $region . ".ini";
    }

}

?>