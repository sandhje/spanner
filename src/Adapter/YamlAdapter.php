<?php

namespace Sandhje\Spanner\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\ArrayAdapter;
use Sandhje\Spanner\Resource\ResourceInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class YamlAdapter extends ArrayAdapter
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