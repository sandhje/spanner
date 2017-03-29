<?php
namespace OpenSourcerers\Spanner\Resource;

use OpenSourcerers\Spanner\Resource\Strategy\ResourceStrategyInterface;
/**
 *
 * @author Sandhje
 *        
 */
interface ResourceInterface
{
    /**
     * Create a resource object from the passed data and set its strategy
     * 
     * @param unknown $resource
     * @param ResourceStrategyInterface $strategy
     */
    public function __construct($resource, ResourceStrategyInterface $strategy); 
    
    /**
     * Load an item from the resource
     *
     * @param string $item
     * @param string|array $environment
     * @return array|bool
     */
    public function load($item, $environment = false);
    
    /**
     * Try to load an item from the resource
     * 
     * @param array &$result
     * @param string $item
     * @param string|array $environment
     * @return bool
     */
    public function tryLoad(&$result, $item, $environment = false);
}
