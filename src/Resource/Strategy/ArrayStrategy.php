<?php
namespace Sandhje\Spanner\Resource\Strategy;

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
        if(is_array($content));
        
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
        return $region . ".php";
    }
}

?>