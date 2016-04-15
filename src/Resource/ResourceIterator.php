<?php
namespace Sandhje\Spanner\Resource;

/**
 *
 * @author Sandhje
 *        
 */
class ResourceIterator implements \Iterator
{
    /**
     * Iterator source
     * 
     * @var array
     */
    private $data;

    public function __construct(array $data)
    {
        foreach($data as $resource) {
            if(!($resource instanceof ResourceInterface))
                throw new \InvalidArgumentException('Argument 1 passed to ResourceIterator::__construct() must be an array of ResourceInterface');
        }
        
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