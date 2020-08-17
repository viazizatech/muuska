<?php
namespace muuska\instantiator;

class Controllers
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Controllers
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @param \muuska\asset\AssetSetter $assetSetter
	 * @return \muuska\controller\DefaultControllerResult
	 */
	public function createDefaultControllerResult(\muuska\asset\AssetSetter $assetSetter) {
	    return new \muuska\controller\DefaultControllerResult($assetSetter);
	}
	
	/**
	 * @param \muuska\project\Project $project
	 * @param string $subAppName
	 * @param string $lang
	 * @param \muuska\http\Request $request
	 * @param \muuska\http\Response $response
	 * @param string $action
	 * @param array $pathParams
	 * @param \muuska\security\CurrentUser $currentUser
	 * @param \muuska\http\VisitorInfoRecorder $visitorInfoRecorder
	 * @param \muuska\dao\DAOFactory $daoFactory
	 * @param string $name
	 * @param string $fullName
	 * @param int $requestType
	 * @param bool $outputEnabled
	 * @param \muuska\util\variation\VariationTrigger[] $variationTriggers
	 * @return \muuska\controller\ControllerInput
	 */
	public function createControllerInput(\muuska\project\Project $project, $subAppName, $lang, \muuska\http\Request $request, \muuska\http\Response $response, $action, $pathParams, \muuska\security\CurrentUser $currentUser, \muuska\http\VisitorInfoRecorder $visitorInfoRecorder, \muuska\dao\DAOFactory $daoFactory, $name, $fullName, $requestType, $outputEnabled, $variationTriggers) {
	    return new \muuska\controller\ControllerInput($project, $subAppName, $lang, $request, $response, $action, $pathParams, $currentUser, $visitorInfoRecorder, $daoFactory, $name, $fullName, $requestType, $outputEnabled, $variationTriggers);
	}
	
	/**
	 * @param \muuska\controller\Controller $source
	 * @param \muuska\controller\ControllerInput $controllerInput
	 * @param \muuska\url\ControllerUrlCreator $urlCreator
	 * @param \muuska\controller\ControllerResult $controllerResult
	 * @param \muuska\controller\param\ControllerParamResolver $paramResolver
	 * @param array $params
	 * @return \muuska\controller\event\ControllerEvent
	 */
	public function createControllerEvent(\muuska\controller\Controller $source, \muuska\controller\ControllerInput $controllerInput, \muuska\url\ControllerUrlCreator $urlCreator, \muuska\controller\ControllerResult $controllerResult, \muuska\controller\param\ControllerParamResolver $paramResolver, $params = array()) {
	    return new \muuska\controller\event\ControllerEvent($source, $controllerInput, $urlCreator, $controllerResult, $paramResolver, $params);
	}
	
	/**
	 * @param \muuska\controller\Controller $source
	 * @param \muuska\controller\ControllerInput $controllerInput
	 * @param \muuska\url\ControllerUrlCreator $urlCreator
	 * @param \muuska\controller\ControllerResult $controllerResult
	 * @param \muuska\controller\param\ControllerParamResolver $paramResolver
	 * @param array $params
	 * @return \muuska\controller\event\ControllerActionProcessingEvent
	 */
	public function createControllerActionProcessingEvent(\muuska\controller\Controller $source, \muuska\controller\ControllerInput $controllerInput, \muuska\url\ControllerUrlCreator $urlCreator, \muuska\controller\ControllerResult $controllerResult, \muuska\controller\param\ControllerParamResolver $paramResolver, $params = array()) {
	    return new \muuska\controller\event\ControllerActionProcessingEvent($source, $controllerInput, $urlCreator, $controllerResult, $paramResolver, $params);
	}
	
	/**
	 * @param \muuska\controller\Controller $source
	 * @param \muuska\html\HtmlPage $htmlPage
	 * @param \muuska\html\areacreator\AreaCreatorEditor $areaCreatorEditor
	 * @param \muuska\controller\ControllerInput $controllerInput
	 * @param \muuska\url\ControllerUrlCreator $urlCreator
	 * @param \muuska\controller\ControllerResult $controllerResult
	 * @param \muuska\controller\param\ControllerParamResolver $paramResolver
	 * @param array $params
	 * @return \muuska\controller\event\ControllerPageFormatingEvent
	 */
	public function createControllerPageFormatingEvent(\muuska\controller\Controller $source, \muuska\html\HtmlPage $htmlPage, \muuska\html\areacreator\AreaCreatorEditor $areaCreatorEditor, \muuska\controller\ControllerInput $controllerInput, \muuska\url\ControllerUrlCreator $urlCreator, \muuska\controller\ControllerResult $controllerResult, \muuska\controller\param\ControllerParamResolver $paramResolver, $params = array()) {
	    return new \muuska\controller\event\ControllerPageFormatingEvent($source, $htmlPage, $areaCreatorEditor, $controllerInput, $urlCreator, $controllerResult, $paramResolver, $params);
	}
	
	/**
	 * @param \muuska\controller\ControllerInput $controllerInput
	 * @param \muuska\controller\DefaultControllerResult $controllerResult
	 * @param \muuska\controller\param\ControllerParamParser[] $parsers
	 * @return \muuska\controller\param\DefaultControllerParamResolver
	 */
	public function createDefaultControllerParamResolver(\muuska\controller\ControllerInput $controllerInput, \muuska\controller\DefaultControllerResult $controllerResult, $parsers = array()) {
	    return new \muuska\controller\param\DefaultControllerParamResolver($controllerInput, $controllerResult, $parsers);
	}
	
	/**
	 * @param string $name
	 * @param boolean $required
	 * @param array $definition
	 * @return \muuska\controller\param\DefaultControllerParamParser
	 */
	public function createDefaultControllerParamParser($name, $required = false, $definition = null) {
	    return new \muuska\controller\param\DefaultControllerParamParser($name, $required, $definition);
	}
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param string $name
	 * @param boolean $required
	 * @param array $definition
	 * @return \muuska\controller\param\ModelControllerParamParser
	 */
	public function createModelControllerParamParser(\muuska\model\ModelDefinition $modelDefinition, $name, $required = false, $definition = null) {
	    return new \muuska\controller\param\ModelControllerParamParser($modelDefinition, $name, $required, $definition);
	}
	
	/**
	 * @param string $name
	 * @param string $value
	 * @param object $object
	 * @return \muuska\controller\param\DefaultControllerParam
	 */
	public function createDefaultControllerParam($name, $value, object $object = null) {
	    return new \muuska\controller\param\DefaultControllerParam($name, $value, $object);
	}
	
	/**
	 * @param \muuska\controller\ControllerInput $input
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param array $virtualDefinition
	 * @return \muuska\controller\VirtualCrudController
	 */
	public function createVirtualCrudController(\muuska\controller\ControllerInput $input, \muuska\model\ModelDefinition $modelDefinition, $virtualDefinition = null) {
	    return new \muuska\controller\VirtualCrudController($input, $modelDefinition, $virtualDefinition);
	}
}
