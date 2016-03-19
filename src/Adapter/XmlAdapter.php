<?php

namespace Sandhje\Spanner\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\ArrayAdapter;
use Sandhje\Spanner\Resource\ResourceInterface;

class XmlAdapter extends ArrayAdapter
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
        
        libxml_use_internal_errors(true);
        
        $config = simplexml_load_string($config, "SimpleXMLElement", LIBXML_NOCDATA);
        
        if(!$config) {
            $errors = libxml_get_errors();
            
            if(count($errors)) {
                throw new \Exception("Invalid configuration file. Error: ". $errors[0]);
            }
            
            libxml_clear_errors();
        }
        
        $config = json_decode(json_encode($config), true);
        
        if(json_last_error()) {
            throw new \Exception("Invalid configuration file. Error: " . json_last_error_msg());
        }
        
        return $config;
    }
    
    protected function getFileName($region)
    {
        return $region . ".xml";
    }

}

?>