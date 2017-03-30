<?php
namespace OpenSourcerers\Spanner\Config;

/**
 *
 * @author Sandhje
 *        
 */
class ConfigElementFactory
{
    /**
     * Create the config collection or item
     * 
     * @param string $element
     * @param string $region
     * @param string $name
     * @return \OpenSourcerers\Spanner\Config\ConfigCollection|\OpenSourcerers\Spanner\Config\ConfigItem
     */
    public function __invoke($element, $region, $name = null)
    {
        if(is_array($element)) {
            return new ConfigCollection($region, $element);
        }
        
        return new ConfigItem($region, $name, $element);
    }
}
