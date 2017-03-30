<?php
namespace OpenSourcerers\Spanner\Resource\Strategy;

/**
 *
 * @author Sandhje
 *        
 */
interface ResourceStrategyInterface
{
    /**
     * Translate the loaded content to an associative array
     * 
     * @param unknown $content
     * @return array
     */
    public function translate($content); 
}
