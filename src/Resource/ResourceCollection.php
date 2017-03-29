<?php
namespace OpenSourcerers\Spanner\Resource;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
/**
 *
 * @author Sandhje
 *        
 */
class ResourceCollection implements \IteratorAggregate
{
    /**
     * resource collection data source
     * 
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        foreach($data as $resource) {
            if(!($resource instanceof ResourceInterface))
                throw new \InvalidArgumentException('Argument 1 passed to ResourceCollection::__construct() must be an array of ResourceInterface');
        }
        
        $this->data = $data;
    }
    
    /**
     * {@inheritDoc}
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new ResourceIterator($this->data);
    }
    
    /**
     * Get element from collection by key
     * 
     * @param scalar $key
     */
    public function get($key)
    {
        if(!is_scalar($key)) {
            throw new \InvalidArgumentException("Resource allows only scalar keys");
        }
        
        if(!array_key_exists($key, $this->data)) {
            return false;
        }
        
        return $this->data[$key];
    }
    
    /**
     * Copy the resource data to an array
     */
    public function toArray()
    {
        $data = $this->data;
        
        return $data;
    }
}
