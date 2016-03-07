<?php
namespace Sandhje\Config\Adapter;

use Sandhje\Config\Config;
/**
 *
 * @author Sandhje
 *        
 */
interface AdapterInterface
{
    /**
     * Load the config items for the passed region
     * 
     * @param Sandhje\Config\Config $config
     * @param string $region
     */
    function load(Config $config, $region);
}

?>