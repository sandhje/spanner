<?php
namespace Sandhje\Spanner\Resource;

use Sandhje\Spanner\Filesystem\Filesystem;
use Sandhje\Spanner\Resource\Strategy\LocalFilesystemStrategyInterface;
use Sandhje\Spanner\Resource\Strategy\LocalFilesystemFileStrategy;
use Sandhje\Spanner\Resource\Strategy\LocalFilesystemDirectoryStrategy;
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
     * Filesystem resource strategy
     * 
     * @var LocalFilesystemStrategyInterface
     */
    private $strategy;
    
    /**
     * Filesystem wrapper class
     *
     * @var Filesystem
     */
    private $filesystem;

    public function __construct($resource, LocalFilesystemStrategyInterface $resourceStrategy = null, Filesystem $filesystem = null)
    {
        $this->filesystem = (!$filesystem ? new Filesystem() : $filesystem);
        
        if (!$this->filesystem->is_readable($resource)) {
            throw new \InvalidArgumentException("Passed resource is not readable");
        }
        
        $this->resource = $resource;
        
        if ($resourceStrategy) {
            $this->strategy = $resourceStrategy;
        } else if ($this->filesystem->is_file($resource)) {    
            $this->strategy = new LocalFilesystemFileStrategy($this->filesystem);
        } else {
            $this->strategy = new LocalFilesystemDirectoryStrategy($this->filesystem);
        }
    }

    /**
     * {@inheritDoc}
     * @see \Sandhje\Spanner\Resource\ResourceInterface::load()
     */
    public function load($item, $environment = false)
    {
        return $this->strategy->loadFile($this->resource, $item, $environment);
    }

}

?>