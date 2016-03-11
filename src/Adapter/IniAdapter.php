<?php

namespace Sandhje\Spanner\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\ArrayAdapter;
use Sandhje\Spanner\Filesystem\Filesystem;

class IniAdapter extends ArrayAdapter
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