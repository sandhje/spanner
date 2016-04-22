<?php
namespace Sandhje\Spanner\Config;

use Sandhje\Spanner\Config\ConfigElementFactory;

/**
 *
 * @author Sandhje
 *        
 */
class ConfigIterator extends ConfigElement implements \Iterator
{
    /**
     * Iterator source
     * 
     * @var array
     */
    private $data;

    public function __construct($region, array $data)
    {
        parent::__construct($region);
        
        $this->data = $data;
    }
    
    /**
     * {@inheritDoc}
     * @see Iterator::current()
     */
    public function current()
    {
        $factory = new ConfigElementFactory();
        return $factory(current($this->data), $this->region, key($this->data));
    }

    /**
     * {@inheritDoc}
     * @see Iterator::key()
     */
    public function key()
    {
        return key($this->data);
    }

    /**
     * {@inheritDoc}
     * @see Iterator::next()
     */
    public function next()
    {
        next($this->data);            
    }

    /**
     * {@inheritDoc}
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        reset($this->data);
    }

    /**
     * {@inheritDoc}
     * @see Iterator::valid()
     */
    public function valid()
    {
        return !is_null(key($this->data));
    }
}
