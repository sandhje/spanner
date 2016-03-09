<?php

namespace Sandhje\Spanner\Adapter;

use Sandhje\Spanner\Config;
use Sandhje\Spanner\Adapter\AdapterInterface;
use Sandhje\Spanner\Adapter\BaseAdapter;
use Sandhje\Spanner\Filesystem\Filesystem;

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