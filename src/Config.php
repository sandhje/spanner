<?php
namespace Sandhje\Spanner;

use Sandhje\Spanner\Config\ConfigElementFactory;
use Sandhje\Spanner\Resource\ResourceInterface;
use Sandhje\Spanner\Resource\ResourceMediator;
use Sandhje\Spanner\Resource\ResourceMediatorInterface;
use Sandhje\Spanner\Environment\EnvironmentCollection;

class Config
{
    /**
     * Configuration cache
     * 
     * @var array
     */
    private $regions = array();
    
    /**
     * Storage for overrides for configuration settings
     * 
     * @var array
     */
    private $regionOverrides = array();
    
    /**
     * ResourceMediator
     * 
     * @var ResoureMediatorInterface
     */
    private $resourceMediator;
    
    /**
     * The environment
     * 
     * @var string
     */
    private $environment;
    
    public function __construct()
    {}
    
    public function setResourceMediator(ResourceMediatorInterface $mediator)
    {
        $this->resourceMediator = $mediator;
    }
    
    public function getResourceMediator()
    {
        if(!$this->resourceMediator)
            $this->setResourceMediator(new ResourceMediator());
        
        return $this->resourceMediator;
    }
    
    /**
     * Attach a resource to the configuration resources mediator
     * 
     * @param ResourceInterface $resource
     * @return int|\Sandhje\Spanner\Config
     */
    public function attachResource(ResourceInterface $resource, $returnIdentifier = false)
    {
        $identifier = $this->getResourceMediator()->attach($resource);
        
        $this->clearCache();
        
        return ($returnIdentifier ? $identifier : $this);
    }
    
    /**
     * Detach a resource from the configuration resources mediator
     * 
     * @param string $identifier
     * @return \Sandhje\Spanner\Config
     */
    public function detachResource($identifier)
    {
        $this->getResourceMediator()->detach($identifier);
        
        return $this;
    }
    
    /**
     * Set the configuration environment
     * 
     * @param mixed $environment
     * @throws \InvalidArgumentException
     * @return \Sandhje\Spanner\Config
     */
    public function setEnvironment($environment)
    {
        if(is_string($environment)) {
            $environment = array($environment);
        }
        
        if(!is_array($environment)) {
            throw new \InvalidArgumentException("Environment has to be a string or array");
        }
        
        foreach($environment as $segment) {
            if(!is_string($segment)) {
                throw new \InvalidArgumentException("The environment array should only contain strings");
            }
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
        if(empty($this->environment))
            return null;
        
        return new EnvironmentCollection($this->environment);
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
        $regionKey = $this->getRegionKey($region);
        
        if(!array_key_exists($regionKey, $this->regions)) {
            if(!$this->load($region)) {
                throw new \Exception("Unable to load configuration region $region");
            }
        }
    
        $data = $this->regions[$regionKey];
        $data = $this->applyOverrides($region, $data);
        
        $factory = new ConfigElementFactory();
        
        if(empty($name)) {
            return $factory($data, $region);
        }
    
        if(!array_key_exists($name, $data)) {
            throw new \Exception("Configuration key $name does not exist in region $region");
        }
    
        return $factory($data[$name], $region, $name);
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
        $override = array($name => $value);
        $regionKey = $this->getRegionKey($region); 
        
        if(!array_key_exists($regionKey, $this->regionOverrides)) {
            $this->regionOverrides[$regionKey] = [];
        }
        
        $this->regionOverrides[$regionKey] = array_replace_recursive($this->regionOverrides[$regionKey], $override);
        
        return $this;
    }
    
    /**
     * Get the region array key
     * 
     * @param string $region
     * @return string
     */
    private function getRegionKey($region)
    {
        return $region . "Config";
    }
    
    /**
     * Apply overrides to the configuration data
     * 
     * @param string $region
     * @param array $data
     * @return array
     */
    private function applyOverrides($region, $data)
    {
        $regionKey = $this->getRegionKey($region);
        
        if(!array_key_exists($regionKey, $this->regionOverrides)) {
            return $data;
        }
        
        return array_replace_recursive($data, $this->regionOverrides[$regionKey]);
    }
    
    /**
     * Clear the configuration cache
     * 
     * @param string $region
     */
    private function clearCache($region = null)
    {
        if($region) {
            $regionKey = $this->getRegionKey($region);
            
            if(array_key_exists($regionKey, $this->regions)) {
                unset($this->regions[$regionKey]);
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
        
        $configRegion = $this->getResourceMediator()->load($region, $this->getEnvironment());
        
        if(!empty($configRegion)) {
            $this->regions[$regionKey] = $configRegion;
            return true;
        }
        
        return false;
    }
}
