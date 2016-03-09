<?php
namespace Sandhje\Spanner\Adapter;

/**
 *
 * @author Sandhje
 *        
 */
class BaseAdapter
{
    protected function mergeConfig($baseConfig, $environmentConfig)
    {
        if(is_array($baseConfig) && is_array($environmentConfig)) {
            return array_replace_recursive($baseConfig, $environmentConfig);
        } else if(is_array($environmentConfig)) {
            return $environmentConfig;
        } else if(is_array($baseConfig)) {
            return $baseConfig;
        } else {
            return false;
        }
    }
}

?>