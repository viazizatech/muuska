<?php
namespace muuska\instantiator;

class Configs
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Configs
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @param string $fileName
	 * @return \muuska\config\source\XMLConfiguration
	 */
	public function createXMLConfiguration($fileName) {
	    return new \muuska\config\source\XMLConfiguration($fileName);
	}
	
	/**
	 * @param string $fileName
	 * @return \muuska\config\source\JSONConfiguration
	 */
	public function createJSONConfiguration($fileName) {
	    return new \muuska\config\source\JSONConfiguration($fileName);
	}
	
	/**
	 * @param array $array
	 * @return \muuska\config\source\BaseConfiguration
	 */
	public function createBaseConfiguration($array = array()) {
	    return new \muuska\config\source\BaseConfiguration($array);
	}
	
	/**
	 * @param \muuska\dao\DAO $dao
	 * @return \muuska\config\source\DAOConfiguration
	 */
	public function createDAOConfiguration(\muuska\dao\DAO $dao) {
	    return new \muuska\config\source\DAOConfiguration($dao);
	}
	
	/**
	 * @param \muuska\config\Configuration $parentConfig
	 * @param string $keyInParent
	 * @param array $array
	 * @return \muuska\config\SubConfiguration
	 */
	public function createSubConfiguration(\muuska\config\Configuration $parentConfig, $keyInParent, $array = array()) {
	    return new \muuska\config\SubConfiguration($parentConfig, $keyInParent, $array);
	}
	
	/**
	 * @return \muuska\config\model\ConfigModel
	 */
	public function createConfigModel() {
	    return new \muuska\config\model\ConfigModel();
	}
	
	/**
	 * @return \muuska\config\model\ConfigModelDefinition
	 */
	public function getConfigModelDefinitionInstance() {
	    return \muuska\config\model\ConfigModelDefinition::getInstance();
	}
}
