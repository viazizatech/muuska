<?php
namespace muuska\controller\event;

use muuska\util\event\EventObject;

class ControllerEvent extends EventObject
{
    /**
     * @var \muuska\controller\ControllerInput
     */
    protected $controllerInput;
    
    /**
     * @var \muuska\controller\ControllerResult
     */
    protected $controllerResult;
    
    /**
     * @var \muuska\controller\param\ControllerParamResolver
     */
    protected $paramResolver;
    
    /**
     * @var \muuska\url\ControllerUrlCreator
     */
    protected $urlCreator;
	
    /**
     * @param \muuska\controller\Controller $source
     * @param \muuska\controller\ControllerInput $controllerInput
     * @param \muuska\url\ControllerUrlCreator $urlCreator
     * @param \muuska\controller\ControllerResult $controllerResult
     * @param \muuska\controller\param\ControllerParamResolver $paramResolver
     * @param array $params
     */
    public function __construct(\muuska\controller\Controller $source, \muuska\controller\ControllerInput $controllerInput, \muuska\url\ControllerUrlCreator $urlCreator, \muuska\controller\ControllerResult $controllerResult, \muuska\controller\param\ControllerParamResolver $paramResolver, $params = array()){
		parent::__construct($source, $params);
        $this->controllerInput = $controllerInput;
        $this->urlCreator = $urlCreator;
        $this->controllerResult = $controllerResult;
        $this->paramResolver = $paramResolver;
	}
	
	/**
	 * @return \muuska\project\SubProject
	 */
	public function getSubProject()
	{
	    return $this->controllerInput->getSubProject();
	}
	
	/**
	 * @return \muuska\project\SubApplication
	 */
	public function getSubApplication()
	{
	    return $this->controllerInput->getSubApplication();
	}
	
	/**
	 * @return bool
	 */
	public function hasTheme()
	{
	    return $this->controllerInput->hasTheme();
	}
	
	/**
	 * @return \muuska\util\theme\Theme
	 */
	public function getTheme()
	{
	    return $this->controllerInput->getTheme();
	}
	
	/**
	 * @return string
	 */
	public function getSubAppNamePrefix()
	{
	    return strtolower($this->getSubAppName()).'_';
	}
	
	/**
	 * @return string
	 */
	public function getFinalEventCode($code)
	{
	    return $this->getSubAppNamePrefix().'controller_'.$code;
	}
	
    /**
     * @return \muuska\controller\ControllerInput
     */
	public function getControllerInput()
    {
        return $this->controllerInput;
    }
    
    /**
     * @return \muuska\controller\param\ControllerParamResolver
     */
    public function getParamResolver()
    {
        return $this->paramResolver;
    }
    
    /**
     * @return \muuska\controller\ControllerResult
     */
    public function getControllerResult()
    {
        return $this->controllerResult;
    }

    /**
     * @return \muuska\url\ControllerUrlCreator
     */
    public function getUrlCreator()
    {
        return $this->urlCreator;
    }
    
    /**
     * @return string
     */
    public function getSubAppName()
    {
        return $this->controllerInput->getSubAppName();
    }
    
    /**
     * @return \muuska\project\Project
     */
    public function getProject()
    {
        return $this->controllerInput->getProject();
    }
}
