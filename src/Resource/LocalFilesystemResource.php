<?php
namespace Sandhje\Spanner\Resource;

use Sandhje\Spanner\Filesystem\Filesystem;
use Sandhje\Spanner\Resource\LocalFilesystemResource\LocalFilesystemStateInterface;
use Sandhje\Spanner\Resource\LocalFilesystemResource\LocalFilesystemFileState;
use Sandhje\Spanner\Resource\LocalFilesystemResource\LocalFilesystemDirectoryState;

/**
 *
 * @author Sandhje
 *        
 */
class LocalFilesystemResource implements ResourceInterface
{
    /**
     * Filesystem path or file
     * 
     * @var string
     */
    private $resource;
    
    /**
     * Filesystem resource state
     * 
     * @var LocalFilesystemStateInterface
     */
    private $state;
    
    /**
     * Filesystem wrapper class
     *
     * @var Filesystem
     */
    private $filesystem;

    public function __construct($resource)
    {
        $this->setResource($resource);
    }

    /**
     * @param \Sandhje\Spanner\Resource\LocalFilesystemResource\StateInterface $state
     */
    public function setState(LocalFilesystemStateInterface $state = null)
    {
        $this->state = $state;
    }
    
    /**
     * @return \Sandhje\Spanner\Resource\LocalFilesystemResource\StateInterface
     */
    private function getState()
    {
        if(!$this->state) {
            if ($this->getFilesystem()->is_file($this->resource)) {
                $this->setState(new LocalFilesystemFileState($this->getFilesystem()));
            } else {
                $this->setState(new LocalFilesystemDirectoryState($this->getFilesystem()));
            }
        }
        
        return $this->state;
    }

    /**
     * @param \Sandhje\Spanner\Filesystem\Filesystem $filesystem
     */
    public function setFilesystem(Filesystem $filesystem = null)
    {
        $this->filesystem = $filesystem;
    }
    
    /**
     * @return \Sandhje\Spanner\Filesystem\Filesystem
     */
    private function getFilesystem()
    {
        if(!$this->filesystem) {
            $this->setFilesystem(new Filesystem());
        }
        
        return $this->filesystem;
    }
    
    /**
     * @param string $resource
     */
    private function setResource($resource)
    {
        $this->resource = $resource;
    }
    
    /**
     * @throws \Exception
     * @return string
     */
    private function getResource()
    {
        if(!$this->resource || !$this->getFilesystem()->is_readable($this->resource)) {
            throw new \Exception("Invalid resource");
        }
        
        return $this->resource;
    }

    /**
     * {@inheritDoc}
     * @see \Sandhje\Spanner\Resource\ResourceInterface::load()
     */
    public function load($item, $environment = false)
    {
        return $this->getState()->loadFile($this->getResource(), $item, $environment);
    }

}

?>