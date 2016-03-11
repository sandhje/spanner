<?php

namespace Sandhje\Spanner\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\ArrayAdapter;
use Sandhje\Spanner\Filesystem\Filesystem;

class XmlAdapter extends ArrayAdapter
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