<?php

namespace Sandhje\Spanner\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\AdapterInterface;
use Sandhje\Spanner\Adapter\BaseAdapter;
use Sandhje\Spanner\Resource\ResourceInterface;

class ArrayAdapter extends BaseAdapter implements AdapterInterface
{
    /**
     * {@inheritDoc}
     * @see \Sandhje\Config\Adapter\ConfigAdapterInterface::load()
     */
    public function load(Config $config, $region)
    {
        $configurationFile = $this->getFileName($region);
        
        $region = array();
        
        $resources = $config->getResourceCollection();
        
        foreach($resources->getIterator() as $resource) {
            $partial = $this->loadFile($resource, $configurationFile);
            
            if(!empty($config->getEnvironment())) {
                $partial = $this->mergeConfig($partial, $this->loadFile($resource, $configurationFile, $config->getEnvironment()));
            }
            
            if(!empty($partial)) {
                $region = $this->mergeConfig($region, $partial);
            }
        }
        
        return $region;
    }
    
    /**
     * Load the passed file from the resource
     * 
     * @param ResourceInterface $resource
     * @param string $file
     * @param string $environment
     * @return array
     */
    protected function loadFile(ResourceInterface $resource, $file, $environment = false) 
    {
        return $resource->load($file, $environment);
    }
    
    protected function getFileName($region)
    {
        return $region . ".php";
    }

}

?>