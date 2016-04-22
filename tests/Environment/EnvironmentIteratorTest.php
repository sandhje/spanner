<?php
namespace Environment;

use Sandhje\Spanner\Environment\EnvironmentIterator;
/**
 *
 * @author Sandhje
 *        
 */
class EnvironmentIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testIteratorArray()
    {
        // Arrange
        $array = array(
            "acme",
            "foo", 
            "bar"
        );
        $iterator_results = [
            ["acme"],
            ["foo"],
            ["acme", "foo"],
            ["bar"],
            ["acme", "bar"],
            ["acme", "foo", "bar"],
        ];
        $result_keys = [];
        $result_values = [];
        
        // Act
        $environmentIterator = new EnvironmentIterator($array);
        foreach($environmentIterator as $key => $value) {
            $result_keys[] = $key;
            $result_values[] = $value;
        }
        
        // Assert
        $this->assertEquals(array_keys($iterator_results), $result_keys);
        $this->assertEquals($iterator_results, $result_values);
    }
}

?>