<?php
namespace Sandhje\Spanner\Resource;

use Sandhje\Spanner\Resource\ResourceInterface;
/**
 *
 * @author Sandhje
 *        
 */
interface ResourceMediatorInterface
{
    /**
     * Attach a resource
     * 
     * @param ResourceInterface $resource
     * @return string
     */
    public function attach(ResourceInterface $resource);
    
    /**
     * Detach a resource
     * 
     * @param string $identifier
     */
    public function detach($identifier);
    
    /**
     * Load a the config data for the passed region and environment
     * 
     * @param string $region
     * @param string|array $environment
     */
    public function load($region, $environment);
    
    /**
     * Merge the passed arrays recursively
     * 
     * @param array $array,...
     * @return array
     */
    public function merge();
}

?>