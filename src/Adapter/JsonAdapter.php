<?php

namespace Sandhje\Spanner\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\ArrayAdapter;
use Sandhje\Spanner\Resource\FilesystemResourceInterface;

class JsonAdapter extends ArrayAdapter
{
    /**
     * Load the passed file and return its contents
     * 
     * @param FilesystemResourceInterface $resource
     * @param string $file
     * @param string $environment
     * @return array
     */
    protected function loadFile(FilesystemResourceInterface $resource, $file, $environment = false) 
    {
        $config = parent::loadFile($resource, $file, $environment);
        
        $config = json_decode($config, true);
        
        if(json_last_error()) {
            throw new \Exception("Invalid configuration file. Error: " . json_last_error_msg());
        }
        
        return $config;
    }
    
    protected function getFileName($region)
    {
        return $region . ".json";
    }

}

?>