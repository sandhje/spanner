<?php
namespace OpenSourcerers\Spanner\Resource\Strategy;

/**
 *
 * @author Sandhje
 *        
 */
class ArrayStrategy implements ResourceStrategyInterface, FilesystemResourceStrategyInterface
{

    /**
     * (non-PHPdoc)
     *
     * @see \Resource\Strategy\ResourceStrategyInterface::translate()
     *
     */
    public function translate($content)
    {
        if(empty($content))
            return array();
        
        if(!is_array($content))
            throw new \Exception("Invalid configuration file.");
        
        return $content;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Resource\Strategy\FilesystemResourceStrategyInterface::getFilename()
     *
     */
    public function getFilename($region)
    {
        return array(
            $region . ".php"
        );
    }
}
