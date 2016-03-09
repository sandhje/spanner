<?php

namespace Sandhje\Spanner\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\AdapterInterface;
use Sandhje\Spanner\Adapter\BaseAdapter;
use Sandhje\Spanner\Filesystem\Filesystem;

class ArrayAdapter extends BaseAdapter implements AdapterInterface
{
    private $filesystem;
    
    public function __construct(Filesystem $filesystem = null)
    {
        $this->filesystem = (!$filesystem ? new Filesystem() : $filesystem);
    }
    
    /**
     * {@inheritDoc}
     * @see \Sandhje\Config\Adapter\ConfigAdapterInterface::load()
     */
    public function load(Config $config, $region)
    {
        $configurationFile = $region . '.php';
        
        $region = array();
        
        if(is_array($config->getPathArray())) {
            foreach($config->getPathArray() as $path) {
                $partial = $this->loadFile($path . '/' . $configurationFile);
                
                if(!empty($config->getEnvironment())) {
                    $partial = $this->mergeConfig($partial, $this->loadFile($path. '/' . $config->getEnvironment() . '/' . $configurationFile));
                }
                
                if(!empty($partial)) {
                    $region = $this->mergeConfig($region, $partial);
                }
            }
        }        
        
        return $region;
    }
    
    /**
     * Load the passed file and return its contents
     * 
     * @param string $file
     * @return mi
     */
    private function loadFile($file) 
    {
        $config = array();
        
        if($this->filesystem->is_file($file) && $this->filesystem->is_readable($file)) {
            $config = $this->filesystem->load($file);
        }
        
        return $config;
    }

}

?>