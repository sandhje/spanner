<?php
namespace Sandhje\Spanner\Config;

/**
 *
 * @author Sandhje
 *        
 */
class ConfigElementFactory
{
    public function __invoke($element, $region, $name = null)
    {
        if(is_array($element)) {
            return new ConfigCollection($region, $element);
        }
        
        return new ConfigItem($region, $name, $element);
    }
}

?>