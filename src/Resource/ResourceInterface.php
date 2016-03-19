<?php
namespace Sandhje\Spanner\Resource;

/**
 *
 * @author Sandhje
 *        
 */
interface ResourceInterface
{
    /**
     * Try to load an item from the resource
     *
     * @param string $item
     * @param string $environment
     * @return string|bool
     */
    public function load($item, $environment = false);
}

?>