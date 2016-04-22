<?php
namespace Sandhje\Spanner\Resource\Strategy;

/**
 *
 * @author Sandhje
 *        
 */
class XmlStrategy implements ResourceStrategyInterface, FilesystemResourceStrategyInterface
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
        
        libxml_use_internal_errors(true);
        
        $content = simplexml_load_string($content, "SimpleXMLElement", LIBXML_NOCDATA);
        
        if(!$content) {
            $errors = libxml_get_errors();
            
            if(count($errors)) {
                throw new \Exception("Invalid configuration file. Error: ". $errors[0]->message);
            }
            
            libxml_clear_errors();
        }
        
        $content = json_decode(json_encode($content), true);
        
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
        return array($region . ".xml");
    }
}
