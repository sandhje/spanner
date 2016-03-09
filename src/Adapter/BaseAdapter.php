<?php
namespace Sandhje\Spanner\Adapter;

/**
 *
 * @author Sandhje
 *        
 */
class BaseAdapter
{
    
    protected function mergeConfig()
    {
        $result = array();
        
        $arg_list = func_get_args();
        for ($i = 0; $i < func_num_args(); $i++) {
            if(is_array($arg_list[$i])) {
                $result = array_replace_recursive($result, $arg_list[$i]);
            }
        }
        
        return $result;
    }
}

?>