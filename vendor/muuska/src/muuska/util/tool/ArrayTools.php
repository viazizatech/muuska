<?php
namespace muuska\util\tool;

use muuska\util\App;

class ArrayTools
{
	private static $instance;
	
	protected function __construct(){}
	
    /**
     * @return \muuska\util\tool\ArrayTools
     */
    final public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }
    
	/**
	 * @param array $array
	 * @param \muuska\getter\Getter $valueGetter
	 * @param string $separator
	 * @return string
	 */
    public function join($array, \muuska\getter\Getter $valueGetter, $separator = ',')
    {
		$string = '';
        $first = true;
        if(is_iterable($array)){
            foreach ($array as $row) {
                if ($first) {
                    $first = false;
                }else{
                    $string .= $separator;
                }
                $string .= $valueGetter->get($row);
            }
        }
        return $string;
    }
    
    /**
     * @param array $array
     * @param \muuska\checker\Checker $checker
     * @param mixed $notFoundKey
     * @return mixed
     */
    public function findKey($array, \muuska\checker\Checker $checker, $notFoundKey = null){
        $result = $notFoundKey;
        if(is_iterable($array)){
            foreach ($array as $key => $row) {
                if ($checker->check($row)) {
                    $result = $key;
                    break;
                }
            }
        }
        return $result;
    }
	
    /**
     * @param array $array
     * @param \muuska\checker\Checker $checker
     * @param mixed $notFoundKey
     * @return boolean
     */
    public function inArray($array, \muuska\checker\Checker $checker, $notFoundKey = null)
    {
        return ($this->findKey($array, $checker, $notFoundKey) !== null);
    }
    
    /**
     * @param array $array
     * @param \muuska\checker\Checker $checker
     * @param mixed $notFoundKey
     * @return mixed
     */
    public function findRow($array, \muuska\checker\Checker $checker, $notFoundKey = null){
        $key = $this->findKey($array, $checker, $notFoundKey);
        return (($key !== $notFoundKey) && isset($array[$key])) ? $array[$key] :  null;
    }
    
    /**
     * @param array $array
     * @param \muuska\checker\Checker $checker
     * @param bool $cleanIndexes
     * @return array
     */
    public function removeRow($array, \muuska\checker\Checker $checker, $cleanIndexes = true){
        $result = array();
        $key = $this->findKey($array, $checker, null);
        if($key !== null){
            unset($array[$key]);
            if ($cleanIndexes) {
                $result = array();
                if(is_iterable($array)){
                    foreach ($array as $row) {
                        $result[] = $row;
                    }
                }
            }
        }
        return $result;
    }
    
    /**
     * @param array $array
     * @param mixed $value
     * @param bool $strict
     * @param bool $cleanIndexes
     * @return array
     */
    public function removeValue($array, $value, $strict = false, $cleanIndexes = true){
        return $this->removeRow($array, App::checkers()->createValueChecker(App::getters()->createSameValueGetter(), $value, null, $strict), $cleanIndexes);
    }
    
    /**
     * @param array $array
     * @param \muuska\getter\Getter $valueGetter
     * @return array
     */
    public function getArrayValues($array, \muuska\getter\Getter $valueGetter)
    {
        $result = array();
        if(is_iterable($array)){
            foreach($array as $row){
                $result[] = $valueGetter->get($row);
            }
        }
        return $result;
    }
	
	/**
	 * @param array $array
	 * @param string $prefix
	 * @return array
	 */
	public function getArrayDataFromKeyPrefix($array, $prefix)
    {
		$result = array();
		if(is_iterable($array)){
		    foreach($array as $key => $row){
		        if(strpos($key, $prefix) === 0){
		            $result[$key] = $row;
		        }
		    }
		}
		return $result;
    }
	
	/**
	 * @param array $array
	 * @return mixed
	 */
	public function getFirstKey($array)
    {
	    $keys = array_keys($array);
	    return isset($keys[0]) ? $keys[0] : null;
    }
	
	/**
	 * @param array $array
	 * @return mixed
	 */
	public function getFirstValue($array)
    {
		$firstKey = $this->getFirstKey($array);
		return isset($array[$firstKey]) ? $array[$firstKey] : null;
    }
	
	/**
	 * @param array $originalArray
	 * @param array $arrayModelDefinition
	 * @param boolean $returnEmptyIfOriginalEmpty
	 * @return array
	 */
	public function formatAssociativeArray($originalArray, $arrayModelDefinition, $returnEmptyIfOriginalEmpty = true)
    {
		$result = $originalArray;
		if(!empty($arrayModelDefinition)){
			if(!empty($originalArray) || !$returnEmptyIfOriginalEmpty){
				foreach($arrayModelDefinition as $arrayKey => $definition){
					$value = null;
					if(array_key_exists($arrayKey, $originalArray)){
						$value = $originalArray[$arrayKey];
					}else{
						$defaultValue = isset($definition['default']) ? $definition['default'] : null;
						$value = $defaultValue;
					}
					$result[$arrayKey] = $value;
				}
			}
		}
		return $result;
    }
    
    /**
     * @param array $array
     * @param \muuska\getter\Getter $valueGetter
     * @param \muuska\getter\Getter $labelGetter
     * @return \muuska\option\provider\AbstractOptionProvider
     */
    public function getOptionProvider($array, \muuska\getter\Getter $valueGetter, \muuska\getter\Getter $labelGetter)
    {
        $options = array();
        if(is_iterable($array)){
            foreach($array as $row){
                $options[$valueGetter->get($row)] = $labelGetter->get($row);
            }
        }
        return App::options()->createKeyValueOptionProvider($options);
    }
}
