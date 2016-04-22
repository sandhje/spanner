<?php
namespace Sandhje\Spanner\Environment;

/**
 *
 * @author Sandhje
 *        
 */
class EnvironmentIterator implements \Iterator
{
    /**
     * Iterator source
     *
     * @var array
     */
    private $data;
    
    public function __construct(array $data)
    {
        $this->data = [];
        
        $dataCount = count($data);
        for($segment = 0; $segment < $dataCount; $segment++) {
            for($cycle = 0; $cycle <= $segment; $cycle++) {
                $cycleData = array_filter(array_flip($data), function($value) use ($segment, $cycle) {
                    if($segment == $value) return true; // Include the segment itself
                    if($value < $cycle) return true; // Include higher level environments
                    return false;
                });
                $this->data[] = array_values(array_intersect_key($data, array_flip($cycleData)));
            };
        }
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