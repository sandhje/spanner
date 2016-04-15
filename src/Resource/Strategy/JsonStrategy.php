<?php
namespace Sandhje\Spanner\Resource\Strategy;

/**
 *
 * @author Sandhje
 *        
 */
class JsonStrategy implements ResourceStrategyInterface, FilesystemResourceStrategyInterface
{

    /**
     * (non-PHPdoc)
     *
     * @see \Resource\Strategy\ResourceStrategyInterface::translate()
     *
     */
    public function translate($content)
    {
        $content = json_decode($content, true);
        
        if(json_last_error()) {
            throw new \Exception("Invalid configuration file. Error: " . json_last_error_msg());
        }
        
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
        return $region . ".json";
    }
}

?>