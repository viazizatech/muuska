<?php
namespace muuska\instantiator;

class Localizations
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Localizations
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @return \muuska\localization\model\LanguageModel
	 */
	public function createLanguageModel() {
	    return new \muuska\localization\model\LanguageModel();
	}
	
	/**
	 * @return \muuska\localization\model\LanguageModelDefinition
	 */
	public function getLanguageModelDefinitionInstance() {
	    return \muuska\localization\model\LanguageModelDefinition::getInstance();
	}
}
