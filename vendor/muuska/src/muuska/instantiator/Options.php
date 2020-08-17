<?php
namespace muuska\instantiator;

class Options
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Options
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @param array $array
	 * @return \muuska\option\AssociativeArrayOption
	 */
	public function createAssociativeArrayOption($array){
	    return new \muuska\option\AssociativeArrayOption($array);
	}
	
	/**
	 * @param array $array
	 * @return \muuska\option\provider\AssociativeArrayOptionProvider
	 */
	public function createAssociativeArrayOptionProvider($array){
	    return new \muuska\option\provider\AssociativeArrayOptionProvider($array);
	}
	
	/**
	 * @param array $array
	 * @return \muuska\option\provider\KeyValueOptionProvider
	 */
	public function createKeyValueOptionProvider($array){
	    return new \muuska\option\provider\KeyValueOptionProvider($array);
	}
	
	/**
	 * @param string $lang
	 * @return \muuska\option\provider\SubAppNameProvider
	 */
	public function createSubAppNameProvider($lang = null) {
	    return new \muuska\option\provider\SubAppNameProvider($lang);
	}
	
	/**
	 * @param string $lang
	 * @return \muuska\option\provider\BoolProvider
	 */
	public function createBoolProvider($lang = null) {
	    return new \muuska\option\provider\BoolProvider($lang);
	}
	
	/**
	 * @param string $lang
	 * @return \muuska\option\provider\GenderProvider
	 */
	public function createGenderProvider($lang = null) {
	    return new \muuska\option\provider\GenderProvider($lang);
	}
	
	/**
	 * @param string $lang
	 * @return \muuska\option\provider\StatusProvider
	 */
	public function createStatusProvider($lang = null) {
	    return new \muuska\option\provider\StatusProvider($lang);
	}
	
	/**
	 * @param string $lang
	 * @return \muuska\option\provider\ApprovalProvider
	 */
	public function createApprovalProvider($lang = null) {
	    return new \muuska\option\provider\ApprovalProvider($lang);
	}
}
