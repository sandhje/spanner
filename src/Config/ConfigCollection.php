<?php
namespace Sandhje\Spanner\Config;

/**
 *
 * @author Sandhje
 *        
 */
class ConfigCollection extends ConfigElement implements \IteratorAggregate
{
    /**
     * Config collection data source
     * 
     * @var array
     */
    private $data;

    public function __construct($region, array $data)
    {
        parent::__construct($region);
        
        $this->data = $data;
    }
    
    /**
     * {@inheritDoc}
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new ConfigIterator($this->data);
    }
    
    /**
     * Get element from collection by key
     * 
     * @param scalar $key
     */
    public function get($key)
    {
        if(!is_scalar($key)) {
            throw new \InvalidArgumentException("ConfigItem allows only scalar keys");
        }
        
        if(!array_key_exists($key, $this->data)) {
            return false;
        }
        
        if(is_array($this->data[$key])) {
            return new ConfigCollection($this->data[$key]);
        }
        
        return new ConfigItem($key, $this->data[$key]);
    }
    
    /**
     * Copy the configuration data to an array
     */
    public function toArray()
    {
        $data = $this->data;
        
        return $data;
    }
}

?>