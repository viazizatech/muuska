<?php
namespace muuska\instantiator;

class Models
{
	private static $instance;
	protected function __construct(){
		
	}
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @param string $fullClassName
	 * @return object
	 */
	public static function createModelFromClass($fullClassName){
		return new $fullClassName(); 
	}
	
	/**
	 * @return \muuska\model\ArrayModel
	 */
	public static function createArrayModel(){
	    return new \muuska\model\ArrayModel();
	}
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param array $data
	 * @return \muuska\model\ModelCollection
	 */
	public static function createModelCollection(\muuska\model\ModelDefinition $modelDefinition, array $data = array()){
	    return new \muuska\model\ModelCollection($modelDefinition, $data);
	}
}
