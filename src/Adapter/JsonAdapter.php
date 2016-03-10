<?php

namespace Sandhje\Spanner\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\ArrayAdapter;
use Sandhje\Spanner\Filesystem\Filesystem;

class JsonAdapter extends ArrayAdapter
{
    public function __construct(Filesystem $filesystem = null)
    {
        parent::__construct($filesystem);
    }
    
    /**
     * Load the passed file and return its contents
     * 
     * @param string $file
     * @return array
     */
    protected function loadFile($file) 
    {
        $config = parent::loadFile($file);
        
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