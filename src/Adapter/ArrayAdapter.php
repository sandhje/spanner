<?php

namespace Sandhje\Config\Adapter;

use Sandhje\Config\Config;
use Sandhje\Config\Adapter\AdapterInterface;
use Sandhje\Config\Adapter\BaseAdapter;

class ArrayAdapter extends BaseAdapter implements AdapterInterface
{
    /**
     * {@inheritDoc}
     * @see \Sandhje\Config\Adapter\ConfigAdapterInterface::load()
     */
    public function load(Config $config, $region)
    {
        $configurationFile = $region . '.php';
        
        if(is_file($config->getPath() . '/' . $configurationFile)) {
            $baseConfig = (include $config->getPath() . '/' . $configurationFile);
        }
        
        if(is_file($config->getPath() . '/' . $config->getEnvironment() . '/' . $configurationFile)) {
            $environmentConfig = (include $config->getPath() . '/' . $config->getEnvironment() . '/' . $configurationFile);
        }
        
        return $this->mergeConfig($baseConfig, $environmentConfig);        
    }

}

?>