<?php
namespace Sandhje\Spanner\Resource;

use Sandhje\Spanner\Proxy\FilesystemProxy;
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
     * Filesystem proxy class
     *
     * @var FilesystemProxy
     */
    private $filesystemProxy;

    public function __construct($resource)
    {
        $this->setResource($resource);
    }

    /**
     * LocalFilesystemStateInterface $fileState
     */
    public function setFileState(LocalFilesystemStateInterface $fileState = null)
    {
        $this->state = $fileState ?: new LocalFilesystemFileState($this->getFilesystemProxy());
    }
    
    /**
     * LocalFilesystemStateInterface $directoryState
     */
    public function setDirectoryState(LocalFilesystemDirectoryState $directoryState = null)
    {
        $this->state = $directoryState ?: new LocalFilesystemDirectoryState($this->getFilesystemProxy());
    }
    
    /**
     * @return \Sandhje\Spanner\Resource\LocalFilesystemResource\StateInterface
     */
    public function getState()
    {
        if(!$this->state) {
            if ($this->getFilesystemProxy()->is_file($this->resource)) {
                $this->setFileState();
            } else {
                $this->setDirectoryState();
            }
        }
        
        return $this->state;
    }
    
    /**
     * @param \Sandhje\Spanner\Proxy\FilesystemProxy $filesystemProxy
     */
    public function setFilesystemProxy(FilesystemProxy $filesystemProxy = null)
    {
        $this->filesystemProxy = $filesystemProxy ?: new FilesystemProxy();
    }
    
    /**
     * @return \Sandhje\Spanner\Proxy\FilesystemProxy
     */
    public function getFilesystemProxy()
    {
        if(!$this->filesystemProxy) {
            $this->setFilesystemProxy();
        }
        
        return $this->filesystemProxy;
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
        if(!$this->resource || !$this->getFilesystemProxy()->is_readable($this->resource)) {
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