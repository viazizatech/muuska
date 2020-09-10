<?php
namespace muuska\instantiator;

class Securities
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Securities
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @param string $key
	 * @param string $iv
	 * @return \muuska\security\Rijndael
	 */
	public function createRijndael($key, $iv) {
	    return new \muuska\security\Rijndael($key, $iv);
	}
	
	/**
	 * @param string $key
	 * @param string $iv
	 * @return \muuska\security\Blowfish
	 */
	public function createBlowfish($key, $iv) {
	    return new \muuska\security\Blowfish($key, $iv);
	}
	
	/**
	 * @param \muuska\dao\DAOFactory $daoFactory
	 * @param string $subAppName
	 * @param \muuska\http\Request $request
	 * @param \muuska\http\Response $response
	 * @param \muuska\http\VisitorInfoRecorder $visitorInfoRecorder
	 * @param \muuska\security\PersonInfoResolver $personInfoResolver
	 * @return \muuska\security\DefaultCurrentUser
	 */
	public function createDefaultCurrentUser(\muuska\dao\DAOFactory $daoFactory, $subAppName, \muuska\http\Request $request, \muuska\http\Response $response, \muuska\http\VisitorInfoRecorder $visitorInfoRecorder, \muuska\security\PersonInfoResolver $personInfoResolver = null) {
	    return new \muuska\security\DefaultCurrentUser($daoFactory, $subAppName, $request, $response, $visitorInfoRecorder, $personInfoResolver);
	}
	
	/**
	 * @param \muuska\dao\DAO $dao
	 * @param \muuska\dao\util\SelectionConfig $selectionConfig
	 * @param string $authentificationField
	 * @return \muuska\security\DefaultPersonInfoResolver
	 */
	public function createDefaultPersonInfoResolver(\muuska\dao\DAO $dao, \muuska\dao\util\SelectionConfig $selectionConfig = null, $authentificationField = null) {
	    return new \muuska\security\DefaultPersonInfoResolver($dao, $selectionConfig, $authentificationField);
	}
	
	/**
	 * @param string $code
	 * @param \muuska\security\ResourceTree $subResourceTree
	 * @return \muuska\security\ResourceTree
	 */
	public function createResourceTree($code, \muuska\security\ResourceTree $subResourceTree = null) {
	    return new \muuska\security\ResourceTree($code, $subResourceTree);
	}
	
	/**
	 * @return \muuska\security\model\AuthentificationDefinition
	 */
	public function getAuthentificationDefinitionInstance() : \muuska\security\model\AuthentificationDefinition {
	    return \muuska\security\model\AuthentificationDefinition::getInstance();
	}
	
	/**
	 * @return \muuska\security\model\GroupDefinition
	 */
	public function getGroupDefinitionInstance() : \muuska\security\model\GroupDefinition{
	    return \muuska\security\model\GroupDefinition::getInstance();
	}
	
	/**
	 * @return \muuska\security\model\ResourceDefinition
	 */
	public function getResourceDefinitionInstance() : \muuska\security\model\ResourceDefinition {
	    return \muuska\security\model\ResourceDefinition::getInstance();
	}
	
	/**
	 * @return \muuska\security\model\AuthentificationAccessDefinition
	 */
	public function getAuthentificationAccessDefinitionInstance() : \muuska\security\model\AuthentificationAccessDefinition {
	    return \muuska\security\model\AuthentificationAccessDefinition::getInstance();
	}
	
	/**
	 * @return \muuska\security\model\GroupAccessDefinition
	 */
	public function getGroupAccessDefinitionInstance() : \muuska\security\model\GroupAccessDefinition {
	    return \muuska\security\model\GroupAccessDefinition::getInstance();
	}
	
	/**
	 * @return \muuska\security\model\AuthentificationGroupDefinition
	 */
	public function getAuthentificationGroupDefinitionInstance() : \muuska\security\model\AuthentificationGroupDefinition {
	    return \muuska\security\model\AuthentificationGroupDefinition::getInstance();
	}
	
	/**
	 * @return \muuska\security\model\AuthentificationModel
	 */
	public function createAuthentificationModel() {
	    return new \muuska\security\model\AuthentificationModel();
	}
	
	/**
	 * @return \muuska\security\model\AuthentificationGroupModel
	 */
	public function createAuthentificationGroupModel() {
	    return new \muuska\security\model\AuthentificationGroupModel();
	}
	
	/**
	 * @return \muuska\security\model\AuthentificationAccessModel
	 */
	public function createAuthentificationAccessModel() {
	    return new \muuska\security\model\AuthentificationAccessModel();
	}
	
	/**
	 * @return \muuska\security\model\GroupModel
	 */
	public function createGroupModel() {
	    return new \muuska\security\model\GroupModel();
	}
	
	/**
	 * @return \muuska\security\model\GroupAccessModel
	 */
	public function createGroupAccessModel() {
	    return new \muuska\security\model\GroupAccessModel();
	}
	
	/**
	 * @return \muuska\security\model\ResourceModel
	 */
	public function createResourceModel() {
	    return new \muuska\security\model\ResourceModel();
	}
	
	/**
	 * @param string $lang
	 * @return \muuska\security\option\ResourceAccessRuleOptionProvider
	 */
	public function createResourceAccessRuleOptionProvider($lang = null) {
	    return new \muuska\security\option\ResourceAccessRuleOptionProvider($lang);
	}
}
