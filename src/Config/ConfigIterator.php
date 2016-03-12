<?php
namespace Sandhje\Spanner\Config;

/**
 *
 * @author Sandhje
 *        
 */
class ConfigIterator implements \Iterator
{
    /**
     * Iterator source
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
     * @see Iterator::current()
     */
    public function current()
    {
        return current($this->data);
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

?>