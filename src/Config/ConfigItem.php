<?php
namespace Sandhje\Spanner\Config;

/**
 *
 * @author Sandhje
 *        
 */
class ConfigItem extends ConfigElement
{    
    /**
     * Config item key
     * 
     * @var scalar
     */
    private $key;
    
    /**
     * Config item value
     * 
     * @var scalar
     */
    private $value;
    
    public function __construct($region, $key, $value)
    {
        parent::__construct($region);
        
        $this->setKey($key);
        $this->setValue($value);
    }

    /**
     * Get key
     * 
     * @return \Config\scalar
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Get value
     * 
     * @return \Config\scalar
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set key
     * 
     * @param unknown $key
     * @throws \InvalidArgumentException
     */
    private function setKey($key)
    {
        if(!is_scalar($key)) {
            throw new \InvalidArgumentException("ConfigItem allows only scalar keys");
        }
        
        $this->key = $key;
    }

    /**
     * Set value
     * 
     * @param scalar $value
     * @throws \InvalidArgumentException
     */
    private function setValue($value)
    {
        if(!is_scalar($value)) {
            throw new \InvalidArgumentException("ConfigItem allows only scalar values");
        }
        
        $this->value = $value;
    }
 
}

?>