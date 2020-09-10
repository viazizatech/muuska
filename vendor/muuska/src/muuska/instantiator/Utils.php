<?php
namespace muuska\instantiator;

class Utils
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Utils
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @return \muuska\util\tool\FileTools
	 */
	public function getFileToolsInstance(){
	    return \muuska\util\tool\FileTools::getInstance();
	}
	
	/**
	 * @return \muuska\util\tool\StringTools
	 */
	public function getStringToolsInstance(){
	    return \muuska\util\tool\StringTools::getInstance();
	}
	
	/**
	 * @return \muuska\util\tool\Tools
	 */
	public function getToolsInstance(){
	    return \muuska\util\tool\Tools::getInstance();
	}
	
	/**
	 * @return \muuska\util\tool\ArrayTools
	 */
	public function getArrayToolsInstance(){
	    return \muuska\util\tool\ArrayTools::getInstance();
	}
	
	/**
	 * @param bool $operationExecuted
	 * @param boolean $successfullyExecuted
	 * @param \muuska\http\redirection\Redirection $redirection
	 * @param \muuska\html\HtmlContent $content
	 * @param array $allAlerts
	 * @return \muuska\util\DefaultNavigationResult
	 */
	public function createDefaultNavigationResult($operationExecuted, $successfullyExecuted = false, \muuska\http\redirection\Redirection $redirection = null, \muuska\html\HtmlContent $content = null, $allAlerts = array()) {
	    return new \muuska\util\DefaultNavigationResult($operationExecuted, $successfullyExecuted, $redirection, $content, $allAlerts);
	}
	
	/**
	 * @return \muuska\util\event\DefaultEventTrigger
	 */
	public function createDefaultEventTrigger() {
	    return new \muuska\util\event\DefaultEventTrigger();
	}
	
	/**
	 * @param \muuska\project\Application $source
	 * @param array $params
	 * @return \muuska\util\event\AppInitializationEvent
	 */
	public function createAppInitializationEvent(\muuska\project\Application $source, $params = array()) {
	    return new \muuska\util\event\AppInitializationEvent($source, $params);
	}
	
	/**
	 * @param object $source
	 * @param array $params
	 * @return \muuska\util\event\EventObject
	 */
	public function createEventObject($source, $params = array()) {
	    return new \muuska\util\event\EventObject($source, $params);
	}
	
	/**
	 * @param array $data
	 * @param \muuska\dao\DAO $dao
	 * @return \muuska\util\ModelListFilter
	 */
	public function createModelListFilter($data, \muuska\dao\DAO $dao) {
	    return new \muuska\util\ModelListFilter($data, $dao);
	}
	
	/**
	 * @param \muuska\controller\ControllerInput $controllerInput
	 * @param \muuska\url\ControllerUrlCreator $controllerUrlCreator
	 * @param \muuska\asset\AssetSetter $assetSetter
	 * @return \muuska\util\setup\SetupInput
	 */
	public function createSetupInput(\muuska\controller\ControllerInput $controllerInput, \muuska\url\ControllerUrlCreator $controllerUrlCreator, \muuska\asset\AssetSetter $assetSetter) {
	    return new \muuska\util\setup\SetupInput($controllerInput, $controllerUrlCreator, $assetSetter);
	}
	
	/**
	 * @param string $name
	 * @param string $corePath
	 * @param \muuska\config\Configuration $mainConfig
	 * @return \muuska\util\theme\DefaultTheme
	 */
	public function createDefaultTheme($name, $corePath, \muuska\config\Configuration $mainConfig = null) {
	    return new \muuska\util\theme\DefaultTheme($name, $corePath, $mainConfig);
	}
	
	/**
	 * @param \muuska\util\theme\Theme $theme
	 * @return \muuska\util\setup\DefaultThemeInstaller
	 */
	public function createDefaultThemeInstaller(\muuska\util\theme\Theme $theme) {
	    return new \muuska\util\setup\DefaultThemeInstaller($theme);
	}
}
