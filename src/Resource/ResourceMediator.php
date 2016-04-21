<?php
namespace Sandhje\Spanner\Resource;

use Sandhje\Spanner\Resource\ResourceMediatorInterface;
/**
 *
 * @author Sandhje
 *        
 */
class ResourceMediator implements ResourceMediatorInterface
{
    /**
     * Resource array
     * 
     * @var array
     */
    private $resources = [];
    
    public function getResourceCollection()
    {
        return new ResourceCollection($this->resources);
    }
    
    /**
     * {@inheritDoc}
     * @see \Sandhje\Spanner\Resource\ResourceMediatorInterface::attach()
     */
    public function attach(ResourceInterface $resource)
    {
        $id = $this->createIdentifier();
        
        $this->resources[$id] = $resource;
        
        return $id;
    }

    /**
     * {@inheritDoc}
     * @see \Sandhje\Spanner\Resource\ResourceMediatorInterface::detach()
     */
    public function detach($identifier)
    {
        if(array_key_exists($identifier, $this->resources))
            unset($this->resources[$identifier]);
    }

    /**
     * {@inheritDoc}
     * @see \Sandhje\Spanner\Resource\ResourceMediatorInterface::load()
     */
    public function load($region, $environmentCollection = null)
    {
        $resourceCollection = $this->getResourceCollection();
        
        $results = [];
        foreach($resourceCollection->getIterator() as $resource) {
            $resourceResults = [];
            
            $resourceResult = [];
            if($resource->tryLoad($resourceResult, $region)) {
                $resourceResults[] = $resourceResult;
            }
            
            if(!empty($environmentCollection)) {                
                foreach($environmentCollection->getIterator() as $environment) {
                    $resourceResult = [];
                    if($resource->tryLoad($resourceResult, $region, $environment)) {
                        $resourceResults[] = $resourceResult;
                    }
                }
            }
            
            $results[] = call_user_func_array(array($this, 'merge'), $resourceResults);
        }
        
        return call_user_func_array(array($this, 'merge'), $results);
    }

    /**
     * {@inheritDoc}
     * @see \Sandhje\Spanner\Resource\ResourceMediatorInterface::merge()
     */
    public function merge()
    {
        $result = array();
        
        $argList = func_get_args();
        
        for ($i = 0; $i < func_num_args(); $i++) {
            if(is_array($argList[$i])) {
                $result = array_replace_recursive($result, $argList[$i]);
            }
        }
        
        return $result;
    }

    private function createIdentifier()
    {
        $id = uniqid();
        
        if(array_key_exists($id, $this->resources)) {
            usleep(1);
            return $this->createIdentifier();
        }
        
        return $id;
    }
}

?>