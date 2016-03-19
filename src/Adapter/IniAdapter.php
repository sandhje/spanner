<?php

namespace Sandhje\Spanner\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\ArrayAdapter;
use Sandhje\Spanner\Resource\ResourceInterface;

class IniAdapter extends ArrayAdapter
{
    /**
     * Load the passed file and return its contents
     * 
     * @param ResourceInterface $resource
     * @param string $file
     * @param string $environment
     * @return array
     */
    protected function loadFile(ResourceInterface $resource, $file, $environment = false) 
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