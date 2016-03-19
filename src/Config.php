<?php
namespace Sandhje\Spanner;

use Sandhje\Spanner\Adapter\AdapterInterface;
use Sandhje\Spanner\Adapter\ArrayAdapter;
use Sandhje\Spanner\Config\ConfigElementFactory;
use Sandhje\Spanner\Resource\LocalFilesystemResource;
use Sandhje\Spanner\Resource\ResourceCollection;

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
     * Configuration resources
     * 
     * @var array
     */
    private $resources = array();
    
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
     * Append a resource to the configuration resources array
     * 
     * @param string|ResourceInterface $resource
     * @throws \InvalidArgumentException
     * @return \Sandhje\Spanner\Config
     */
    public function appendResource($resource)
    {
        $resource = $this->prepResource($resource);
        
        $this->resources[] = $resource;
        
        $this->clearCache();
        
        return $this;
    }
    
    /**
     * Prepend a resource to the configuration resources array
     * 
     * @param string|ResourceInterface $resource
     * @throws \InvalidArgumentException
     * @return \Sandhje\Spanner\Config
     */
    public function prependResource($resource)
    {
        $resource = $this->prepResource($resource);
        
        array_unshift($this->resources, $resource);
        
        $this->clearCache();
        
        return $this;
    }
    
    /**
     * Set the configuration resources array
     * 
     * @param array $resourceArray
     * @throws \InvalidArgumentException
     * @return \Sandhje\Spanner\Config
     */
    public function setResourceArray(array $resourceArray)
    {
        $resources = array();
        
        foreach($resourceArray as $resource) {
            $resources[] = $this->prepResource($resource);
        }
        
        $this->resources = $resources;
        
        $this->clearCache();
        
        return $this;
    }
    
    /**
     * Get the resource collection
     * 
     * @return Sandhje\Spanner\Resource\ResourceCollection
     */
    public function getResourceCollection()
    {
        return new ResourceCollection($this->resources);
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
     * Prepare and validate a resource for insertion into the resources property
     * 
     * @param string|\Sandhje\Spanner\Resource\LocalFilesystemResource $resource
     * @throws \InvalidArgumentException
     * @return \Sandhje\Spanner\Resource\LocalFilesystemResource
     */
    private function prepResource($resource)
    {
        if(is_string($resource)) {
            $resource = new LocalFilesystemResource($resource);
        }
        
        if(!is_object($resource) || !is_subclass_of($resource, 'Sandhje\Spanner\Resource\ResourceInterface'))
        {
            throw new \InvalidArgumentException("Invalid resource could not be appended to the resources array");
        }
        
        return $resource;
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
        
        $configRegion = $this->adapter->load($this, $region);
        
        if(!empty($configRegion)) {
            $this->regions[$regionKey] = $configRegion;
            return true;
        }
        
        return false;
    }
}