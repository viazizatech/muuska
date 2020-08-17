<?php
namespace muuska\http\event;

use muuska\util\event\EventObject;

class RequestParsingEvent extends EventObject
{
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
     * @var string
     */
    protected $finalPathInfo;
    
    /**
     * @var array
     */
    protected $pathParams;
    
    /**
     * @var string
     */
    protected $projectType;
    
    /**
     * @var string
     */
    protected $projectName;
    
    /**
     * @var string
     */
    protected $controller;
    
    /**
     * @var string
     */
    protected $action;
    
    /**
     * @var \muuska\util\variation\VariationTrigger[]
     */
    protected $variationTriggers;
	
    /**
     * @param \muuska\http\Router $source
     * @param \muuska\http\Request $request
     * @param \muuska\http\Response $response
     * @param string $finalPathInfo
     * @param string $subAppName
     * @param string $lang
     * @param string $projectType
     * @param string $projectName
     * @param string $controller
     * @param string $action
     * @param array $pathParams
     * @param \muuska\util\variation\VariationTrigger[] $variationTriggers
     * @param array $params
     */
    public function __construct(\muuska\http\Router $source, \muuska\http\Request $request, \muuska\http\Response $response, $finalPathInfo = null, $subAppName = null, $lang = null, $projectType = null, $projectName = null, $controller = null, $action = null, $pathParams = array(), $variationTriggers = array(), $params = array()){
		parent::__construct($source, $params);
		$this->request = $request;
		$this->response = $response;
		$this->setFinalPathInfo($finalPathInfo);
		$this->setSubAppName($subAppName);
		$this->setLang($lang);
		$this->setProjectType($projectType);
		$this->setProjectName($projectName);
		$this->setController($controller);
		$this->setAction($action);
		$this->setPathParams($pathParams);
		$this->setVariationTriggers($variationTriggers);
	}
	
	/**
	 * @return string
	 */
	public function getFinalEventCode($code)
	{
	    return (empty($this->subAppName) ? '' : strtolower($this->subAppName) . '_') . 'request_parsing_'.$code;
	}
	
	/**
	 * @return \muuska\http\event\RequestParsingEvent
	 */
	public function createAfterEvent(){
	    $this->defaultPrevented = false;
	    $this->propagationStopped = false;
	    return $this;
	}
	
	/**
	 * @return \muuska\http\event\RequestParsingEvent
	 */
	public function createFinalParsingEvent(){
	    $this->defaultPrevented = false;
	    $this->propagationStopped = false;
	    return $this;
	}
	
	/**
	 * @return bool
	 */
	public function hasAction() {
	    return !empty($this->action);
	}
	
	/**
	 * @return bool
	 */
	public function hasController() {
	    return !empty($this->controller);
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function addPathParam($name, $value) {
	    $this->setPathParam($name, $value);
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function setPathParam($name, $value) {
	    $this->pathParams[$name] = $value;
	}
	
	/**
	 * @param array $params
	 */
	public function addPathParams($params) {
	    if(is_array($params)){
	        foreach ($params as $key => $value) {
	            $this->addPathParam($key, $value);
	        }
	    }
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
	 * @param mixed $defaultValue
	 * @return mixed
	 */
	public function getPathParam($name, $defaultValue = null) {
	    return $this->hasPathParam($name) ? $this->pathParams[$name] : $defaultValue;
	}
	
	/**
	 * @param \muuska\util\variation\VariationTrigger $variationTrigger
	 */
	public function addVariationTrigger(\muuska\util\variation\VariationTrigger $variationTrigger) {
	    $this->variationTriggers[$variationTrigger->getName()] = $variationTrigger;
	}
	
	/**
	 * @param \muuska\util\variation\VariationTrigger[] $variationTriggers
	 */
	public function addVariationTriggers($variationTriggers) {
	    if(is_array($variationTriggers)){
	        foreach ($variationTriggers as $variationTrigger) {
	            $this->addVariationTrigger($variationTrigger);
	        }
	    }
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
     * @return string
     */
    public function getFinalPathInfo()
    {
        return $this->finalPathInfo;
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
    public function getProjectType()
    {
        return $this->projectType;
    }

    /**
     * @return string
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return \muuska\util\variation\VariationTrigger[]
     */
    public function getVariationTriggers()
    {
        return $this->variationTriggers;
    }

    /**
     * @param string $subAppName
     */
    public function setSubAppName($subAppName)
    {
        $this->subAppName = $subAppName;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @param string $finalPathInfo
     */
    public function setFinalPathInfo($finalPathInfo)
    {
        $this->finalPathInfo = $finalPathInfo;
    }

    /**
     * @param array $pathParams
     */
    public function setPathParams($pathParams)
    {
        $this->pathParams = array();
        $this->addPathParams($pathParams);
    }

    /**
     * @param string $projectType
     */
    public function setProjectType($projectType)
    {
        $this->projectType = $projectType;
    }

    /**
     * @param string $projectName
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;
    }

    /**
     * @param string $controller
     */
    public function setController($controller)
    {
        $this->controller = $controller;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * @param \muuska\util\variation\VariationTrigger[] $variationTriggers
     */
    public function setVariationTriggers($variationTriggers)
    {
        $this->variationTriggers = array();
        $this->addVariationTriggers($variationTriggers);
    }
}
