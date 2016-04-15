<?php
namespace Sandhje\Spanner\Resource\Strategy;

/**
 *
 * @author Sandhje
 *        
 */
interface FilesystemResourceStrategyInterface extends ResourceStrategyInterface
{
    /**
     * Get the file to load from the passed region
     * 
     * @param string $region
     * @return string
     */
    public function getFilename($region); 
}

?>