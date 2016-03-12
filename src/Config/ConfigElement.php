<?php
namespace Sandhje\Spanner\Config;

/**
 *
 * @author Sandhje
 *        
 */
abstract class ConfigElement
{
    /**
     * Config element region
     * 
     * @var scalar
     */
    protected $region;
    
    public function __construct($region)
    {
        if(!is_scalar($region)) {
            throw new \InvalidArgumentException("Config region allows only scalar name");
        }
        
        $this->region = $region;
    }

    /**
     * Return region name
     * 
     * @return scalar
     */
    public function getRegion()
    {
        return $this->region;
    }
}

?>