<?php
namespace Sandhje\Spanner\Adapter;

/**
 *
 * @author Sandhje
 *        
 */
class BaseAdapter
{
    /**
     * Merge the passed array's recursively
     * 
     * @return array
     */
    protected function mergeConfig()
    {
        $result = array();
        
        $argList = func_get_args();
        for ($i = 0; $i < func_num_args(); $i++) {
            if(is_array($argList[$i])) {
                $result = array_replace_recursive($result, $argList[$i]);
            }
        }
        
        return $result;
    }
}

?>