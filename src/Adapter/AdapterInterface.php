<?php
namespace Sandhje\Spanner\Adapter;

use Sandhje\Spanner\Config;
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
     * @return array
     */
    function load(Config $config, $region);
}

?>