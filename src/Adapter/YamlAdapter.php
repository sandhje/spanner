<?php

namespace Sandhje\Spanner\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\ArrayAdapter;
use Sandhje\Spanner\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class YamlAdapter extends ArrayAdapter
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
        
        try {
            $config = Yaml::parse($config);
        } catch(ParseException $e) {
            throw new \Exception("Invalid configuration file. Error: " . $e->getMessage());            
        }
           
        return $config;
    }
    
    protected function getFileName($region)
    {
        return $region . ".yml";
    }

}

?>