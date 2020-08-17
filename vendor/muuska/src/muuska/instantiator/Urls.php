<?php
namespace muuska\instantiator;

class Urls
{
	private static $instance;
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Urls
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @param \muuska\controller\ControllerInput $controllerInput
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param string $action
	 * @param array $initialParams
	 * @return \muuska\url\objects\ModelUrl
	 */
	public function createModelUrl(\muuska\controller\ControllerInput $controllerInput, \muuska\model\ModelDefinition $modelDefinition, $action, $initialParams = null){
	    return new \muuska\url\objects\ModelUrl($controllerInput, $modelDefinition, $action, $initialParams);
	}
	
	/**
	 * @param \muuska\controller\ControllerInput $controllerInput
	 * @param array $initialParams
	 * @return \muuska\url\DefaultControllerUrl
	 */
	public function createDefaultControllerUrl(\muuska\controller\ControllerInput $controllerInput, $initialParams = null){
	    return new \muuska\url\DefaultControllerUrl($controllerInput, $initialParams);
	}
	
	/**
	 * @param \muuska\controller\ControllerInput $controllerInput
	 * @param array $initialParams
	 * @param string $defaultUrl
	 */
	public function createArrayUrl(\muuska\controller\ControllerInput $controllerInput,  $initialParams = null, $defaultUrl = null){
	    return new \muuska\url\objects\ArrayUrl($controllerInput,  $initialParams, $defaultUrl);
	}
	
	/**
	 * @param callable $callback
	 * @param array $initialParams
	 * @return \muuska\url\objects\DefaultObjectUrl
	 */
	public function createDefaultObjectUrl($callback, $initialParams = null){
	    return new \muuska\url\objects\DefaultObjectUrl($callback, $initialParams);
	}
	
	/**
	 * @param \muuska\project\Application $source
	 * @param \muuska\url\UrlCreationInput $input
	 * @param array $params
	 * @return \muuska\url\event\UrlCreationEvent
	 */
	public function createUrlCreationEvent(\muuska\project\Application $source, \muuska\url\UrlCreationInput $input, $params = array()){
	    return new \muuska\url\event\UrlCreationEvent($source, $input, $params);
	}
	
	/**
	 * @param string $subAppName
	 * @param string $lang
	 * @param string $controllerName
	 * @param string $action
	 * @param array $params
	 * @param string $projectType
	 * @param string $projectName
	 * @param string $anchor
	 * @param array $variationTriggers
	 * @param int $mode
	 * @return \muuska\url\UrlCreationInput
	 */
	public function createUrlCreationInput($subAppName, $lang, $controllerName, $action = null, $params = array(), $projectType = null, $projectName = null, $anchor = '', $variationTriggers = array(), $mode = null){
	    return new \muuska\url\UrlCreationInput($subAppName, $lang, $controllerName, $action, $params, $projectType, $projectName, $anchor, $variationTriggers, $mode);
	}
}
