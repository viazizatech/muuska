<?php
namespace muuska\instantiator;

class Checkers
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Checkers
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @param callable $callback
	 * @param array $initialParams
	 * @return \muuska\checker\DefaultChecker
	 */
	public function createDefaultchecker($callback, $initialParams = null) {
	    return new \muuska\checker\DefaultChecker($callback, $initialParams);
	}
	
	/**
	 * @param \muuska\getter\Getter $valueGetter
     * @param mixed $expectedValue
     * @param boolean $strict
     * @param int $operator
	 * @return \muuska\checker\ValueChecker
	 */
	public function createValueChecker(\muuska\getter\Getter $valueGetter, $expectedValue, $operator = null, $strict = false) {
	    return new \muuska\checker\ValueChecker($valueGetter, $expectedValue, $operator, $strict);
	}
	
	/**
	 * @param \muuska\checker\Checker[] $checkers
	 * @param int $logicalOperator
	 * @return \muuska\checker\MultipleChecker
	 */
	public function createMultipleChecker($checkers = array(), $logicalOperator = null) {
	    return new \muuska\checker\MultipleChecker($checkers, $logicalOperator);
	}
}
