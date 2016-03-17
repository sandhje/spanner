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
class LocalFilesystemResource implements FilesystemResourceInterface
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
            $this->strategy = new LocalFilesystemFileStrategy($filesystem);
        } else {
            $this->strategy = new LocalFilesystemDirectoryStrategy($filesystem);
        }
    }

    /**
     * {@inheritDoc}
     * @see \Sandhje\Spanner\Resource\ResourceInterface::loadFile()
     */
    public function loadFile($file, $environment = false)
    {
        return $this->strategy->loadFile($this->resource, $file, $environment);
    }

}

?>