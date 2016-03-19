<?php

namespace Sandhje\Spanner\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\ArrayAdapter;
use Sandhje\Spanner\Resource\ResourceInterface;

class JsonAdapter extends ArrayAdapter
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