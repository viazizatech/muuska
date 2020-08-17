<?php
namespace muuska\instantiator;

class Daos
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Daos
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @param string $mainSource
	 * @return \muuska\dao\DAOFactory
	 */
	public function createDAOFactory($mainSource){
	    return new \muuska\dao\DAOFactory($mainSource);
	}
	
	/**
	 * @param string $name
	 * @param \muuska\config\Configuration $configuration
	 * @return \muuska\dao\source\pdo\PDODAOSource
	 */
	public function createPDOSourceFromConfiguration($name = null, \muuska\config\Configuration $configuration = null){
	    return \muuska\dao\source\pdo\PDODAOSource::createFromConfiguration($name, $configuration);
	}
	
	/**
	 * @param string $name
	 * @param string $dsn
	 * @param string $username
	 * @param string $password
	 * @param string $tablePrefix
	 * @param int $timeout
	 * @return \muuska\dao\source\pdo\PDODAOSource
	 */
	public function createPDODAOSource($name, $dsn, $username = null, $password = null, $tablePrefix = null, $timeout = null){
	    return new \muuska\dao\source\pdo\PDODAOSource($name, $dsn, $username, $password, $tablePrefix, $timeout);
	}
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param \muuska\dao\DAOFactory $daoFactory
	 * @param \muuska\dao\source\pdo\PDODAOSource $source
	 * @return \muuska\dao\source\pdo\PDODAO
	 */
	public function createPDODAO(\muuska\model\ModelDefinition $modelDefinition, \muuska\dao\DAOFactory $daoFactory, \muuska\dao\source\pdo\PDODAOSource $source){
	    return new \muuska\dao\source\pdo\PDODAO($modelDefinition, $daoFactory, $source);
	}
	
	/**
	 * @param string $name
	 * @return \muuska\dao\source\json\JSONDAOSource
	 */
	public function createJSONDAOSource($name){
	    return new \muuska\dao\source\json\JSONDAOSource($name);
	}
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param \muuska\dao\DAOFactory $daoFactory
	 * @param \muuska\dao\DAOSource $source
	 * @return \muuska\dao\source\json\JSONDAO
	 */
	public function createJSONDAO(\muuska\model\ModelDefinition $modelDefinition, \muuska\dao\DAOFactory $daoFactory, \muuska\dao\DAOSource $source){
	    return new \muuska\dao\source\json\JSONDAO($modelDefinition, $daoFactory, $source);
	}
	
	/**
	 * @param string $code
	 * @return \muuska\dao\util\DAOFunction
	 */
	public function createDAOFunction($code){
	    return new \muuska\dao\util\DAOFunction($code);
	}
	
	/**
	 * @param mixed $value
	 * @param int $valueType
	 * @return \muuska\dao\util\DAOFunctionParameter
	 */
	public function createDAOFunctionParameter($value, $valueType = null){
	    return new \muuska\dao\util\DAOFunctionParameter($value, $valueType);
	}
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param array $data
	 * @param int $totalWithoutLimit
	 * @return \muuska\dao\util\DAOListResult
	 */
	public function createDAOListResult(\muuska\model\ModelDefinition $modelDefinition, array $data, $totalWithoutLimit = null){
	    return new \muuska\dao\util\DAOListResult($modelDefinition, $data, $totalWithoutLimit);
	}
	
	/**
	 * @param string $fieldName
	 * @param boolean $foreign
	 * @param string $externalField
	 * @return \muuska\dao\util\FieldParameter
	 */
	public function createFieldParameter($fieldName, $foreign = false, $externalField = null){
	    return new \muuska\dao\util\FieldParameter($fieldName, $foreign, $externalField);
	}
	
	/**
	 * @param string $fieldName
	 * @param mixed $value
	 * @param int $operator
	 * @return \muuska\dao\util\FieldRestriction
	 */
	public function createFieldRestriction($fieldName, $value, $operator = null){
	    return new \muuska\dao\util\FieldRestriction($fieldName, $value, $operator);
	}
	
	/**
	 * @param string $lang
	 * @param \muuska\localization\LanguageInfo[] $languages
	 * @return \muuska\dao\util\SaveConfig
	 */
	public function createSaveConfig($lang = null, $languages = array()){
	    return new \muuska\dao\util\SaveConfig($lang, $languages);
	}
	
	/**
	 * @param string $associationName
	 * @param string $lang
	 * @param \muuska\localization\LanguageInfo[] $languages
	 * @return \muuska\dao\util\MultipleSaveAssociation
	 */
	public function createMultipleSaveAssociation($associationName, $lang = null, $languages = array()){
	    return new \muuska\dao\util\MultipleSaveAssociation($associationName, $lang, $languages);
	}
	
	/**
	 * @param string $fieldName
	 * @param boolean $langEnabled
	 * @param boolean $allLangsEnabled
	 * @param int $joinType
	 * @param boolean $retrievingEnabled
	 * @return \muuska\dao\util\SelectionAssociation
	 */
	public function createSelectionAssociation($fieldName, $langEnabled = true, $allLangsEnabled = false, $joinType = null, $retrievingEnabled = true){
	    return new \muuska\dao\util\SelectionAssociation($fieldName, $langEnabled, $allLangsEnabled, $joinType, $retrievingEnabled);
	}
	
	/**
	 * @param string $lang
	 * @return \muuska\dao\util\SelectionConfig
	 */
	public function createSelectionConfig($lang = ''){
	    return new \muuska\dao\util\SelectionConfig($lang);
	}
	
	/**
	 * @param string $associationName
	 * @param string $lang
	 * @return \muuska\dao\util\MultipleSelectionAssociation
	 */
	public function createMultipleSelectionAssociation($associationName, $lang = ''){
	    return new \muuska\dao\util\MultipleSelectionAssociation($associationName, $lang);
	}
	
	/**
	 * @param boolean $virtual
	 * @return \muuska\dao\util\DeleteConfig
	 */
	public function createDeleteConfig($virtual = true){
	    return new \muuska\dao\util\DeleteConfig($virtual);
	}
	
	/**
	 * @param string $fieldName
	 * @param int $direction
	 * @return \muuska\dao\util\SortOption
	 */
	public function createSortOption($fieldName, $direction = null){
	    return new \muuska\dao\util\SortOption($fieldName, $direction);
	}
	
	/**
	 * @param \muuska\dao\DAO $source
	 * @param string $changeCode
	 * @param \muuska\dao\util\DataConfig $dataConfig
	 * @param object $model
	 * @param array $params
	 * @return \muuska\dao\event\DataChangeEvent
	 */
	public function createDataChangeEvent(\muuska\dao\DAO $source, $changeCode, \muuska\dao\util\DataConfig $dataConfig = null, object $model = null, $params = array()){
	    return new \muuska\dao\event\DataChangeEvent($source, $changeCode, $dataConfig, $model, $params);
	}
	
	/**
	 * @param \muuska\dao\DAO $source
	 * @param object $model
	 * @param \muuska\dao\util\SaveConfig $saveConfig
	 * @param array $params
	 * @return \muuska\dao\event\ModelAddEvent
	 */
	public function createModelAddEvent(\muuska\dao\DAO $source, object $model, \muuska\dao\util\SaveConfig $saveConfig = null, $params = array()){
	    return new \muuska\dao\event\ModelAddEvent($source, $model, $saveConfig, $params);
	}
	
	/**
	 * @param \muuska\dao\DAO $source
	 * @param object $model
	 * @param \muuska\dao\util\SaveConfig $saveConfig
	 * @param array $params
	 * @return \muuska\dao\event\ModelUpdateEvent
	 */
	public function createModelUpdateEvent(\muuska\dao\DAO $source, object $model, \muuska\dao\util\SaveConfig $saveConfig = null, $params = array()){
	    return new \muuska\dao\event\ModelUpdateEvent($source, $model, $saveConfig, $params);
	}
	
	/**
	 * @param \muuska\dao\DAO $source
	 * @param object $model
	 * @param \muuska\dao\util\DeleteConfig $deleteConfig
	 * @param array $params
	 * @return \muuska\dao\event\ModelDeleteEvent
	 */
	public function createModelDeleteEvent(\muuska\dao\DAO $source, object $model, \muuska\dao\util\DeleteConfig $deleteConfig = null, $params = array()){
	    return new \muuska\dao\event\ModelDeleteEvent($source, $model, $deleteConfig, $params);
	}
	
	/**
	 * @param \muuska\dao\DAO $source
	 * @param object $model
	 * @param \muuska\dao\util\SaveConfig $saveConfig
	 * @param array $params
	 * @return \muuska\dao\event\MultipleRowsUpdateEvent
	 */
	public function createMultipleRowsUpdateEvent(\muuska\dao\DAO $source, object $model, \muuska\dao\util\SaveConfig $saveConfig = null, $params = array()){
	    return new \muuska\dao\event\MultipleRowsUpdateEvent($source, $model, $saveConfig, $params);
	}
	
	/**
	 * @param \muuska\dao\DAO $source
	 * @param \muuska\dao\util\DeleteConfig $deleteConfig
	 * @param array $params
	 * @return \muuska\dao\event\MultipleRowsDeleteEvent
	 */
	public function createMultipleRowsDeleteEvent(\muuska\dao\DAO $source, \muuska\dao\util\DeleteConfig $deleteConfig = null, $params = array()){
	    return new \muuska\dao\event\MultipleRowsDeleteEvent($source, $deleteConfig, $params);
	}
	
	/**
	 * @param \muuska\dao\DAO $source
	 * @param array $params
	 * @return \muuska\dao\event\DataClearingEvent
	 */
	public function createDataClearingEvent(\muuska\dao\DAO $source, $params = array()){
	    return new \muuska\dao\event\DataClearingEvent($source, $params);
	}
	
	/**
	 * @param \muuska\project\Project $project
	 * @param \muuska\model\ModelDefinition[] $modelDefinitions
	 * @return \muuska\dao\ProjectDAOInstallInput
	 */
	public function createProjectDAOInstallInput(\muuska\project\Project $project, $modelDefinitions = array()){
	    return new \muuska\dao\ProjectDAOInstallInput($project, $modelDefinitions);
	}
	
	/**
	 * @param \muuska\project\Project $project
	 * @param \muuska\model\ModelDefinition[] $modelDefinitions
	 * @return \muuska\dao\ProjectDAOUninstallInput
	 */
	public function createProjectDAOUninstallInput(\muuska\project\Project $project, $modelDefinitions = array()){
	    return new \muuska\dao\ProjectDAOUninstallInput($project, $modelDefinitions);
	}
	
	/**
	 * @param \muuska\project\Project $project
	 * @param \muuska\model\ModelDefinition[] $addedModelDefinitions
	 * @param \muuska\model\ModelDefinition[] $removedModelDefinitions
	 * @param \muuska\dao\ModelDefinitionUpgradeInfo[] $modelDefinitionUpgradeInfos
	 * @return \muuska\dao\ProjectDAOUpgradeInput
	 */
	public function createProjectDAOUpgradeInput(\muuska\project\Project $project, $addedModelDefinitions = array(), $removedModelDefinitions = array(), $modelDefinitionUpgradeInfos = array()){
	    return new \muuska\dao\ProjectDAOUpgradeInput($project, $addedModelDefinitions, $removedModelDefinitions, $modelDefinitionUpgradeInfos);
	}
	
	/**
	 * @param \muuska\model\ModelDefinition $oldModelDefinition
	 * @param \muuska\model\ModelDefinition $newModelDefinition
	 * @return \muuska\dao\ModelDefinitionUpgradeInfo
	 */
	public function createModelDefinitionUpgradeInfo(\muuska\model\ModelDefinition $oldModelDefinition, \muuska\model\ModelDefinition $newModelDefinition){
	    return new \muuska\dao\ModelDefinitionUpgradeInfo($oldModelDefinition, $newModelDefinition);
	}
	
	/**
	 * @param \muuska\validation\result\ModelValidationResult $modelValidationResult
	 * @param mixed $message
	 * @param mixed $code
	 * @param mixed $previous
	 * @return \muuska\dao\exception\InvalidModelException
	 */
	public function createInvalidModelException(\muuska\validation\result\ModelValidationResult $modelValidationResult, $message = null, $code = null, $previous = null){
	    return new \muuska\dao\exception\InvalidModelException($modelValidationResult, $message, $code, $previous);
	}
}
