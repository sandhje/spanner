<?php
namespace Sandhje\Spanner\Environment;

/**
 *
 * @author Sandhje
 *        
 */
class EnvironmentCollection implements \IteratorAggregate
{
    /**
     * Environment collection data source
     *
     * @var array
     */
    private $data;
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    /**
     * {@inheritDoc}
     * @see IteratorAggregate::getIterator()
     */
    public function getIterator()
    {
        return new EnvironmentIterator($this->data);
    }

    /**
     * Copy the environment data to an array
     */
    public function toArray()
    {
        $data = $this->data;
        
        return $data;
    }
}
