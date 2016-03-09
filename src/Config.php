<?php
namespace Sandhje\Spanner;

use Sandhje\Spanner\Adapter\AdapterInterface;
use Sandhje\Spanner\Adapter\ArrayAdapter;

class Config
{
    private $regions = array();
    private $pathArray;
    private $environment;
    private $adapter;
    
    public function __construct(AdapterInterface $adapter = null)
    {
        $this->adapter = (!$adapter ? new ArrayAdapter() : $adapter);
    }
    
    public function appendPath($path)
    {
        if(!is_array($this->pathArray)) {
            $this->pathArray = array();
        }
        
        array_push($this->pathArray, $path);
        return $this;
    }
    
    public function prependPath($path)
    {
        if(!is_array($this->pathArray)) {
            $this->pathArray = array();
        }
        
        array_unshift($this->pathArray, $path);
        return $this;
    }
    
    public function setPathArray(array $pathArray)
    {
        $this->pathArray = $pathArray;
        return $this;
    }
    
    public function getPathArray()
    {
        return $this->pathArray;
    }
    
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
        return $this;
    }
    
    public function getEnvironment()
    {
        return $this->environment;
    }
    
    public function get($region, $name = false)
    {
        if(!array_key_exists($region . "Config", $this->regions)) {
            if(!$this->load($region))
                throw new \Exception("Unable to load configuration region $region", 500);
        }
    
        if(empty($name)) {
            return $this->regions[$region . "Config"];
        }
    
        if(!array_key_exists($name, $this->regions[$region . "Config"])) {
            throw new \Exception("Configuration key $name does not exist in region $region", 500);
        }
    
        return $this->regions[$region . "Config"][$name];
    }
    
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