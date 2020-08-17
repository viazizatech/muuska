<?php
namespace muuska\controller;

use muuska\html\constants\AlertType;
use muuska\http\constants\RequestType;
use muuska\util\App;

class ControllerInput
{
    /**
     * @var \muuska\project\Project
     */
    protected $project;
    
	/**
	 * @var string
	 */
	protected $subAppName;
	
	/**
	 * @var string
	 */
	protected $lang;
	
	/**
	 * @var \muuska\http\Request
	 */
	protected $request;
	
	/**
	 * @var \muuska\http\Response
	 */
	protected $response;
	
	/**
	 * @var \muuska\security\CurrentUser
	 */
	protected $currentUser;
	
	/**
	 * @var \muuska\http\VisitorInfoRecorder
	 */
	protected $visitorInfoRecorder;
	
	/**
	 * @var \muuska\dao\DAOFactory
	 */
	protected $daoFactory;
	
	/**
	 * @var string
	 */
	protected $name;
	
	/**
	 * @var string
	 */
	protected $fullName;
	
	/**
	 * @var \muuska\util\variation\VariationTrigger[]
	 */
	protected $variationTriggers;
	
	/**
	 * @var array
	 */
	protected $pathParams;
	
	/**
	 * @var string
	 */
	protected $action;
	
	/**
	 * @var int
	 */
	protected $requestType;
	
	/**
	 * @var bool
	 */
	protected $outputEnabled;
	
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
	 */
	public function __construct(\muuska\project\Project $project, $subAppName, $lang, \muuska\http\Request $request, \muuska\http\Response $response, $action, $pathParams, \muuska\security\CurrentUser $currentUser, \muuska\http\VisitorInfoRecorder $visitorInfoRecorder, \muuska\dao\DAOFactory $daoFactory, $name, $fullName, $requestType, $outputEnabled, $variationTriggers){
	    $this->project = $project;
	    $this->subAppName = $subAppName;
	    $this->lang = $lang;
	    $this->request = $request;
	    $this->response = $response;
	    $this->action = $action;
	    $this->pathParams = $pathParams;
	    $this->currentUser = $currentUser;
	    $this->visitorInfoRecorder = $visitorInfoRecorder;
	    $this->daoFactory = $daoFactory;
	    $this->name = $name;
	    $this->fullName = $fullName;
	    $this->requestType = $requestType;
	    $this->outputEnabled = $outputEnabled;
	    $this->variationTriggers = $variationTriggers;
	}
	
	/**
	 * @param string $value
	 * @return boolean
	 */
	public function checkName($value){
	    return ($this->name === $value);
	}
	
	/**
	 * @param string $value
	 * @return boolean
	 */
	public function checkFullName($value){
	    return ($this->fullName === $value);
	}
	
	/**
	 * @return bool
	 */
	public function isAjaxRequest(){
	    return ($this->getRequestType() == RequestType::AJAX);
	}
	
	/**
	 * @param \muuska\security\ResourceTree $subResourceTree
	 * @return \muuska\security\ResourceTree
	 */
	public function getControllerResourceTree(\muuska\security\ResourceTree $subResourceTree = null) {
	    return $this->getProject()->createResourceTree($this->getSubAppName(), App::securities()->createResourceTree($this->getName(), $subResourceTree));
	}
	
	/**
	 * @param \muuska\security\ResourceTree $subResourceTree
	 * @return \muuska\security\ResourceTree
	 */
	public function getActionResourceTree(\muuska\security\ResourceTree $subResourceTree = null) {
	    return $this->getControllerResourceTree(App::securities()->createResourceTree($this->getAction(), $subResourceTree));
	}
	
	/**
	 * @param \muuska\security\ResourceTree $subResourceTree
	 * @return \muuska\security\ResourceTree
	 */
	public function getFieldResourceTree($field) {
	    return $this->getActionResourceTree(App::securities()->createResourceTree($field));
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getQueryParam($name, $defaultValue = null){
	    return $this->getPathParam($name, $this->request->getQueryParam($name, $defaultValue));
	}
	
	/**
	 * @param string $name
	 * @return boolean
	 */
	public function hasQueryParam($name){
	    return ($this->hasPathParam($name) || $this->request->hasQueryParam($name));
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getParam($name, $defaultValue = null){
	    return $this->getPathParam($name, $this->request->getParam($name, $defaultValue));
	}
	
	/**
	 * @param string $name
	 * @return boolean
	 */
	public function hasParam($name){
	    return ($this->hasPathParam($name) || $this->request->hasPostParam($name) || $this->request->hasQueryParam($name));
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasPathParam($name) {
	    return isset($this->pathParams[$name]);
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasPostParam($name) {
	    return $this->request->hasPostParam($name);
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getPostParam($name, $defaultValue = null) {
	    return $this->request->getPostParam($name, $defaultValue);
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getPathParam($name, $defaultValue = null) {
	    return $this->hasPathParam($name) ? $this->pathParams[$name] : $defaultValue;
	}
	
	/**
	 * @return \muuska\localization\LanguageInfo[]
	 */
	public function getLanguages() {
	    return App::getApp()->getLanguages(true);
	}
	
	/**
	 * @return \muuska\localization\LanguageInfo
	 */
	public function getLanguageInfo()
	{
	    return App::getApp()->getLanguageInfo($this->getLang());
	}
	
	/**
	 * @param string $text
	 * @param string $context
	 * @return string
	 */
	public function getFrameworkError($text, $context = '') {
	    return App::translateFramework(App::translations()->createAlertTranslationConfig(AlertType::DANGER), $text, $this->getLang(), $context);
	}
	
	/**
	 * @return \muuska\dao\util\SelectionConfig
	 */
	public function createSelectionConfig() {
	    return App::daos()->createSelectionConfig($this->getLang());
	}
	
	/**
	 * @return \muuska\dao\util\SaveConfig
	 */
	public function createSaveConfig() {
	    return App::daos()->createSaveConfig($this->getLang(), $this->getLanguages());
	}
	
	/**
	 * @return \muuska\dao\util\DeleteConfig
	 */
	public function createDeleteConfig() {
	    return App::daos()->createDeleteConfig();
	}
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param string $customDAOSource
	 * @return \muuska\dao\DAO
	 */
	public function getDAO(\muuska\model\ModelDefinition $modelDefinition, $customDAOSource = null) {
	    return $this->daoFactory->getDAO($modelDefinition, $customDAOSource);
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasVariationTrigger($name) {
	    return isset($this->variationTriggers[$name]);
	}
	
	/**
	 * @param string $name
	 * @return \muuska\util\variation\VariationTrigger
	 */
	public function getVariationTrigger($name) {
	    return $this->hasVariationTrigger($name) ? $this->variationTriggers[$name] : null;
	}
	
	/**
	 * @param string $name
	 * @param mixed $defaultValue
	 * @return string
	 */
	public function getVariationTriggerValue($name, $defaultValue = null) {
	    return $this->hasVariationTrigger($name) ? $this->getVariationTrigger($name)->getValue() : $defaultValue;
	}
	
	/**
	 * @return \muuska\project\SubProject
	 */
	public function getSubProject()
	{
	    return $this->getProject()->getSubProject($this->getSubAppName());
	}
	
	/**
	 * @return bool
	 */
	public function hasTheme()
	{
	    return ($this->getTheme() !== null);
	}
	
	/**
	 * @return \muuska\util\theme\Theme
	 */
	public function getTheme()
	{
	    return $this->getSubApplication()->getActiveTheme();
	}
	
	/**
	 * @return \muuska\project\SubApplication
	 */
	public function getSubApplication()
	{
	    return App::getApp()->getSubApplication($this->getSubAppName());
	}
	
    /**
     * @return \muuska\project\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return string
     */
    public function getSubAppName()
    {
        return $this->subAppName;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @return \muuska\http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }
    
    /**
     * @return \muuska\http\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return \muuska\security\CurrentUser
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * @return \muuska\http\VisitorInfoRecorder
     */
    public function getVisitorInfoRecorder()
    {
        return $this->visitorInfoRecorder;
    }

    /**
     * @return \muuska\dao\DAOFactory
     */
    public function getDaoFactory()
    {
        return $this->daoFactory;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @return \muuska\util\variation\VariationTrigger[]
     */
    public function getVariationTriggers()
    {
        return $this->variationTriggers;
    }

    /**
     * @return array
     */
    public function getPathParams()
    {
        return $this->pathParams;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return int
     */
    public function getRequestType()
    {
        return $this->requestType;
    }
    
    /**
     * @return bool
     */
    public function isOutputEnabled()
    {
        return $this->outputEnabled;
    }
}