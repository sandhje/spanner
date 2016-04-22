<?php
namespace Sandhje\Spanner\Resource\Strategy;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
/**
 *
 * @author Sandhje
 *        
 */
class YamlStrategy implements ResourceStrategyInterface, FilesystemResourceStrategyInterface
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
        
        try {
            $content = Yaml::parse($content, true);
        } catch(ParseException $e) {
            throw new \Exception("Invalid configuration file. Error: " . $e->getMessage());            
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
        return array(
            $region . ".yml",
            $region . ".yaml"
        );
    }
}
