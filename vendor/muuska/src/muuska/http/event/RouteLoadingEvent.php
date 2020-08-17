<?php
namespace muuska\http\event;

use muuska\project\constants\ProjectType;
use muuska\util\event\EventObject;

class RouteLoadingEvent extends EventObject
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
     * @param \muuska\http\Router $source
     * @param string $subAppName
     * @param string $lang
     * @param array $params
     */
    public function __construct(\muuska\http\Router $source, $subAppName, $lang, $params = array()){
		parent::__construct($source, $params);
		$this->subAppName = $subAppName;
		$this->lang = $lang;
	}
	
	/**
	 * @return string
	 */
	public function getFinalEventCode()
	{
	    return strtolower($this->subAppName) . '_router_loading';
	}
	
	/**
	 * @param string $projectType
	 * @param string $projectName
	 * @param string $rule
	 * @param string $controller
	 * @param string $action
	 * @param array $keywords
	 * @param array $params
	 * @param string $routeName
	 */
	public function addRoute($projectType, $projectName, $rule, $controller = null, $action = null, $keywords = array(), $params = array(), $routeName = null)
	{
	    $this->source->addRoute($this->subAppName, $this->lang, $projectType, $projectName, $rule, $controller, $action, $keywords, $params, $routeName);
	}
	
	/**
	 * @param array $route
	 * @param string $routeName
	 */
	public function addRouteFromArray($route, $routeName = null)
	{
	    $this->source->addRouteFromArray($this->subAppName, $this->lang, $route, $routeName);
	}
	
	/**
	 * @param string $rule
	 * @param string $controller
	 * @param string $action
	 * @param array $keywords
	 * @param array $params
	 * @param string $routeName
	 */
	public function addAppRoute($rule, $controller = null, $action = null, $keywords = array(), $params = array(), $routeName = null)
	{
	    $this->addRoute(ProjectType::APPLICATION, null, $rule, $controller, $action, $keywords, $params, $routeName);
	}
	
	/**
	 * @param string $moduleName
	 * @param string $rule
	 * @param string $controller
	 * @param string $action
	 * @param array $keywords
	 * @param array $params
	 * @param string $routeName
	 */
	public function addModuleRoute($moduleName, $rule, $controller = null, $action, $keywords = array(), $params = array(), $routeName = null)
	{
	    $this->addRoute(ProjectType::MODULE, $moduleName, $rule, $controller, $action, $keywords, $params, $routeName);
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
}
