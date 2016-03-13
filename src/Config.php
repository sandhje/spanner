<?php
namespace Sandhje\Spanner;

use Sandhje\Spanner\Adapter\AdapterInterface;
use Sandhje\Spanner\Adapter\ArrayAdapter;
use Sandhje\Spanner\Config\ConfigElementFactory;

class Config
{
    /**
     * Configuration cache
     * 
     * @var array
     */
    private $regions = array();
    
    /**
     * Paths to load config files from
     * 
     * @var array
     */
    private $pathArray;
    
    /**
     * The environment
     * 
     * @var string
     */
    private $environment;
    
    /**
     * Configuration adapter
     * 
     * @var AdapterInterface
     */
    private $adapter;
    
    public function __construct(AdapterInterface $adapter = null)
    {
        $this->adapter = (!$adapter ? new ArrayAdapter() : $adapter);
    }
    
    /**
     * Append a path to the configuration path array
     * 
     * @param string $path
     * @throws \InvalidArgumentException
     * @return \Sandhje\Spanner\Config
     */
    public function appendPath($path)
    {
        if(!is_string($path)) {
            throw new \InvalidArgumentException("Path has to be a string");
        }
        
        if(!is_array($this->pathArray)) {
            $this->pathArray = array();
        }
        
        array_push($this->pathArray, $path);
        
        $this->clearCache();
        
        return $this;
    }
    
    /**
     * Prepend a path to the configuration path array
     * 
     * @param string $path
     * @throws \InvalidArgumentException
     * @return \Sandhje\Spanner\Config
     */
    public function prependPath($path)
    {
        if(!is_string($path)) {
            throw new \InvalidArgumentException("Path has to be a string");
        }
        
        if(!is_array($this->pathArray)) {
            $this->pathArray = array();
        }
        
        array_unshift($this->pathArray, $path);
        
        $this->clearCache();
        
        return $this;
    }
    
    /**
     * Set the configuration path array
     * 
     * @param array $pathArray
     * @return \Sandhje\Spanner\Config
     */
    public function setPathArray(array $pathArray)
    {
        $this->pathArray = $pathArray;
        
        $this->clearCache();
        
        return $this;
    }
    
    /**
     * Get a copy of the path array
     * 
     * @return array
     */
    public function getPathArray()
    {
        $pathArray = $this->pathArray;
        
        return $pathArray;
    }
    
    /**
     * Set the configuration environment
     * 
     * @param string $environment
     * @throws \InvalidArgumentException
     * @return \Sandhje\Spanner\Config
     */
    public function setEnvironment($environment)
    {
        if(!is_string($environment)) {
            throw new \InvalidArgumentException("Environment has to be a string");
        }
        
        $this->environment = $environment;
        
        $this->clearCache();
        
        return $this;
    }
    
    /**
     * Get the configuration environment
     * 
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }
    
    /**
     * Get a collection or item from the configuration
     * 
     * @param string $region
     * @param string $name
     * @throws \Exception
     * @return \Sandhje\Spanner\Config\ConfigCollection|\Sandhje\Spanner\Config\ConfigItem
     */
    public function get($region, $name = null)
    {
        $regionKey = $region . "Config";
        
        if(!array_key_exists($regionKey, $this->regions)) {
            if(!$this->load($region))
                throw new \Exception("Unable to load configuration region $region");
        }
    
        $factory = new ConfigElementFactory();
        
        if(empty($name)) {
            return $factory($this->regions[$regionKey], $region);
        }
    
        if(!array_key_exists($name, $this->regions[$regionKey])) {
            throw new \Exception("Configuration key $name does not exist in region $region");
        }
    
        return $factory($this->regions[$regionKey][$name], $region, $name);
    }
    
    /**
     * Set a value for a configuration element
     * 
     * @param string $region
     * @param string $name
     * @param mixed $value
     * @return \Sandhje\Spanner\Config
     */
    public function set($region, $name, $value)
    {        
        // Load from config files if this is not done yet
        $this->get($region, $name);
        
        $regionKey = $region . "Config";
        
        if(!array_key_exists($regionKey, $this->regions)) {
            $this->regions[$regionKey] = array();
        }
        
        if(!array_key_exists($name, $this->regions[$regionKey])) {
            $this->regions[$regionKey][$name] = "";
        }
        
        if(is_array($this->regions[$regionKey][$name]) && is_array($value)) {
            $this->regions[$regionKey][$name] = array_replace_recursive($this->regions[$regionKey][$name], $value);
        } else {
            $this->regions[$regionKey][$name] = $value;
        }
        
        return $this;
    }
    
    /**
     * Clear the configuration cache
     * 
     * @param string $region
     */
    private function clearCache($region = null)
    {
        if($region) {
            if(array_key_exists($region, $this->regions)) {
                unset($this->regions[$region]);
            }
        } else {
            $this->regions = array();
        }
    }
    
    /**
     * Load a configuration region into the configuration cache
     * 
     * @param string $region
     * @return boolean
     */
    private function load($region)
    {
        $regionKey = $region . "Config";
        
        $configRegion = $this->adapter->load($this, $region);
        
        if(!empty($configRegion)) {
            $this->regions[$regionKey] = $configRegion;
            return true;
        }
        
        return false;
    }
}