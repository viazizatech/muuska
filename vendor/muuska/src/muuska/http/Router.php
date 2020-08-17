<?php
namespace muuska\http;

use muuska\project\constants\ProjectType;
use muuska\url\constants\UrlCreationMode;
use muuska\util\App;
use muuska\constants\ActionCode;
use muuska\constants\Names;

class Router{
    /**
     * @var array
     */
    protected $routes = array();
    
	/**
	 * @var array
	 */
	protected $defaultRoutes = array(
		'module' => array(
            'rule' =>        'module/{projectName}{/:controller}{/:action}',
            'keywords' => array(
                'projectName' =>        array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'projectName'),
                'controller' =>        array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'controller', 'optional' => true),
				'action' =>        array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'action', 'optional' => true),
            ),
		    'projectType' => ProjectType::MODULE
        ),
		'application' => array(
            'rule' =>        '{controller}{/:action}',
            'keywords' => array(
                'controller' =>        array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'controller'),
				'action' =>        array('regexp' => '[_a-zA-Z0-9_-]+', 'param' => 'action', 'optional' => true),
            ),
		    'projectType' => ProjectType::APPLICATION
        )
	);
	
    /**
     * @var bool
     */
    protected $rewritingEnabled = true;

    /**
     * @var bool
     */
    protected $multilingualEnabled = false;
    
    /**
     * @var \muuska\dao\DAOFactory
     */
    protected $daoFactory;
    
    /**
     * @param \muuska\dao\DAOFactory $daoFactory
     */
    public function __construct(\muuska\dao\DAOFactory $daoFactory){
        $this->daoFactory = $daoFactory;
        $this->multilingualEnabled = (count(App::getApp()->getLanguages()) > 1);
        $this->rewritingEnabled = App::getApp()->getMainConfiguration()->getBool('rewriting_enabled', true);
    }

    public function run()
    {
        $mainApplication = App::getApp();
		$projectInstalled = $mainApplication->isAppInstalled();
		$mainConfig = $mainApplication->getMainConfiguration();
		$request = $this->createRequest();
		$requestParsingEvent = App::https()->createRequestParsingEvent($this, $request, $this->createResponse($request));
		if(!$projectInstalled){
		    if(!$mainConfig->containsKey('server_host')){
		        $mainConfig->setProperty('server_host', $request->getServerName());
		        $mainConfig->save();
		    }
		    if(!$mainConfig->containsKey('context_path')){
		        $mainConfig->setProperty('context_path', $request->getContextPath());
		        $mainConfig->save();
			}
			if(!$mainConfig->containsKey('ssl_enabled')){
			    $mainConfig->setProperty('ssl_enabled', $request->isSecure());
			    $mainConfig->save();
			}
		}
		if(App::getEventTrigger()->fireRequestParsing('before', $requestParsingEvent)){
		    $this->parseRequest($requestParsingEvent);
		    $requestParsingEvent = $requestParsingEvent->createAfterEvent();
		    App::getEventTrigger()->fireRequestParsing('after', $requestParsingEvent);
		}
		
	    $finalParsingEvent = $requestParsingEvent->createFinalParsingEvent();
	    if($this->rewritingEnabled && !$request->hasQueryParam('controller') && !$requestParsingEvent->hasController()){
		    if(App::getEventTrigger()->fireRequestFinalParsing('before', $finalParsingEvent)){
		        $this->parseFinalRequest($finalParsingEvent);
		        App::getEventTrigger()->fireRequestFinalParsing('after', $finalParsingEvent);
			}
		}
		if(!$requestParsingEvent->hasController()){
		    $requestParsingEvent->setController(Names::HOME_CONTROLLER);
		}
		$subApplication = $mainApplication->getSubApplication($finalParsingEvent->getSubAppName());
		if($subApplication === null){
		    $finalParsingEvent->getResponse()->sendError(501, 'Application not implemented');
		}else{
		    $subApplication->runController($this->daoFactory, $finalParsingEvent, true);
		}
    }

    /**
     * @param \muuska\http\event\RequestParsingEvent $requestEvent
     */
    public function parseRequest(\muuska\http\event\RequestParsingEvent $requestEvent)
    {
		$this->parseRequestDefault($requestEvent);
    }
    
    /**
     * @param \muuska\http\event\RequestParsingEvent $requestEvent
     */
    public function parseRequestDefault(\muuska\http\event\RequestParsingEvent $requestEvent)
    {
        $request = $requestEvent->getRequest();
        $pathInfo = $request->getPathInfo();
        $finalPathInfo = rawurldecode($pathInfo);
        $finalPathInfo = ltrim($finalPathInfo, '/');
        $finalPathInfo = rtrim($finalPathInfo, '/');
        $pathParams = explode('/', $finalPathInfo);
        $enabledSubApps = App::getApp()->getEnabledSubApplications();
        $subAppName = null;
        $subAppConfig = null;
        $subAppCount = count($enabledSubApps);
        if($subAppCount === 0){
            $requestEvent->getResponse()->sendError(501, 'No application available');
        }else if($subAppCount === 1){
            $subAppName = $enabledSubApps[0];
            $subAppConfig = App::getApp()->getSubApplicationConfig($subAppName);
        }else{
            $expectedSubAppUrlPath = isset($pathParams[0]) ? $pathParams[0] : '';
            $serverName = $request->getServerName();
            $finalUrlPath = null;
            foreach ($enabledSubApps as $tmpSubAppName) {
                $tmpSubAppConfig = App::getApp()->getSubApplicationConfig($tmpSubAppName);
                $urlPath = $tmpSubAppConfig->getString('url_path');
                if($serverName === $tmpSubAppConfig->getString('host')){
                    $subAppName = $tmpSubAppName;
                    $subAppConfig = $tmpSubAppConfig;
                    $finalUrlPath = null;
                    break;
                }elseif(($expectedSubAppUrlPath === $urlPath) || empty($urlPath)){
                    $subAppName = $tmpSubAppName;
                    $finalUrlPath = $urlPath;
                    $subAppConfig = $tmpSubAppConfig;
                }
            }
            if(!empty($finalUrlPath) && isset($pathParams[0])){
                unset($pathParams[0]);
                $pathParams = array_values($pathParams);
            }
        }
        if(empty($subAppName) || ($subAppConfig === null)){
            $requestEvent->getResponse()->sendError(501, 'Application not found');
        }else{
            $requestEvent->setSubAppName($subAppName);
        }
        if($this->multilingualEnabled && $subAppConfig->getBool('lang_in_url')){
            if(isset($pathParams[0])){
                $requestEvent->setLang($pathParams[0]);
                unset($pathParams[0]);
                $pathParams = array_values($pathParams);
            }
        }
        $finalPathInfo = '';
        if(!empty($pathParams)){
            $finalPathInfo = '/' . implode('/', $pathParams);
        }
        $requestEvent->setFinalPathInfo($finalPathInfo);
    }
	
	/**
	 * @return \muuska\http\Request
	 */
	protected function createRequest()
    {
		return App::https()->createRequestFromGlobal();
	}
	
	/**
	 * @param \muuska\http\Request $request
	 * @return \muuska\http\Response
	 */
	protected function createResponse(\muuska\http\Request $request)
	{
	    return App::https()->createResponse($request->getProtocol());
	}
	
	/**
	 * @param \muuska\http\event\RequestParsingEvent $finalRequestEvent
	 */
	public function parseFinalRequest(\muuska\http\event\RequestParsingEvent $finalRequestEvent)
    {
		$subAppName = $finalRequestEvent->getSubAppName();
		
		$lang = $finalRequestEvent->getLang();
		if(!isset($this->routes[$subAppName]) || !isset($this->routes[$subAppName][$lang])){
		    $this->loadRoutes($subAppName, $lang);
		}
		list($uri) = explode('?', $finalRequestEvent->getFinalPathInfo());
		if (isset($this->routes[$subAppName]) && isset($this->routes[$subAppName][$lang])) {
		    foreach ($this->routes[$subAppName][$lang] as $route) {
		        $m = array();
				if (preg_match($route['regexp'], $uri, $m)) {
				    $this->addRequestPathParams($finalRequestEvent, $m);
					
					if (isset($route['params']) && !empty($route['params'])) {
					    $this->addRequestPathParams($finalRequestEvent, $route['params']);
					}
					
					if(isset($route['projectType']) && !empty($route['projectType'])){
					    $finalRequestEvent->setProjectType($route['projectType']);
					}
					if(isset($route['projectName']) && !empty($route['projectName'])){
					    $finalRequestEvent->setProjectName($route['projectName']);
					}
					if(isset($route['controller']) && !empty($route['controller'])){
					    $finalRequestEvent->setController($route['controller']);
					}
					if(isset($route['action']) && !empty($route['action'])){
					    $finalRequestEvent->setAction($route['action']);
					}
					break;
				}
			}
		}
	}
    
	/**
	 * @param \muuska\http\event\RequestParsingEvent $finalRequestEvent
	 * @param array $params
	 */
	public function addRequestPathParams(\muuska\http\event\RequestParsingEvent $finalRequestEvent, $params){
	    foreach ($params as $key => $value) {
	        if($key === 'projectType'){
	            $finalRequestEvent->setProjectType($value);
	        }elseif(($key === 'projectName') || ($key === 'module')){
	            $finalRequestEvent->setProjectName($value);
	        }elseif($key === 'controller'){
	            $finalRequestEvent->setController($value);
	        }elseif($key === 'action'){
	            $finalRequestEvent->setAction($value);
	        }elseif(!is_numeric($key)){
	            $finalRequestEvent->addPathParam($key, $value);
	        }
	    }
	}
	
    /**
     * @param string $subAppName
     * @param string $lang
     */
    protected function loadRoutes($subAppName, $lang)
    {
		App::getEventTrigger()->fireRouteLoading(App::https()->createRouteLoadingEvent($this, $subAppName, $lang));
		foreach ($this->defaultRoutes as $routeName => $route) {
		    $this->addRouteFromArray($subAppName, $lang, $route, $routeName);
		}
    }
    
    /**
     * @param string $subAppName
     * @param string $lang
     * @param array $route
     * @param string $routeName
     */
    public function addRouteFromArray($subAppName, $lang, $route, $routeName = null)
    {
		$projectType = isset($route['projectType']) ? $route['projectType'] : null;
		$projectName = isset($route['projectName']) ? $route['projectName'] : null;
		$controller = isset($route['controller']) ? $route['controller'] : null;
		$action = isset($route['action']) ? $route['action'] : null;
		$rule = isset($route['rule']) ? $route['rule'] : null;
		$keywords = isset($route['keywords']) ? $route['keywords'] : array();
		$params = isset($route['params']) ? $route['params'] : array();
		$this->addRoute($subAppName, $lang, $projectType, $projectName, $rule, $controller, $action, $keywords, $params, $routeName);
	}
	
	/**
	 * @param string $subAppName
	 * @param string $lang
	 * @param string $projectType
	 * @param string $projectName
	 * @param string $rule
	 * @param string $controller
	 * @param string $action
	 * @param array $keywords
	 * @param array $params
	 * @param string $routeName
	 */
	public function addRoute($subAppName, $lang, $projectType, $projectName , $rule, $controller = null, $action = null, $keywords = array(), $params = array(), $routeName = null)
    {
	    $m = array();
		$regexp = preg_quote($rule, '#');
        if ($keywords) {
            $transformKeywords = array();
            preg_match_all('#\\\{(([^{}]*)\\\:)?('.implode('|', array_keys($keywords)).')(\\\:([^{}]*))?\\\}#', $regexp, $m);
            for ($i = 0, $total = count($m[0]); $i < $total; $i++) {
                $prepend = $m[2][$i];
                $keyword = $m[3][$i];
                $append = $m[5][$i];
				
                $transformKeywords[$keyword] = array(
                    'required' =>    (isset($keywords[$keyword]['param']) && (!isset($keywords[$keyword]['optional']) || !$keywords[$keyword]['optional'])),
                    'prepend' =>    stripslashes($prepend),
                    'append' =>        stripslashes($append),
                );
                $prependRegexp = $appendRegexp = '';
                if ($prepend || $append) {
                    $prependRegexp = '('.$prepend;
                    $appendRegexp = $append.')?';
                }

                if (isset($keywords[$keyword]['param'])) {
                    $regexp = str_replace($m[0][$i], $prependRegexp.'(?P<'.$keywords[$keyword]['param'].'>'.$keywords[$keyword]['regexp'].')'.$appendRegexp, $regexp);
                } else {
                    $regexp = str_replace($m[0][$i], $prependRegexp.'('.$keywords[$keyword]['regexp'].')'.$appendRegexp, $regexp);
                }
            }
            $keywords = $transformKeywords;
        }

        $regexp = '#^/'.$regexp.'$#u';
        if(empty($routeName)){
            $routeName = $this->getRouteName($projectType, $projectName, $controller, $action);
        }
        if(!empty($projectType)){
            $params['projectType'] = $projectType;
        }
        if(!empty($projectName)){
            $params['projectName'] = $projectName;
        }
        if(!empty($controller)){
            $params['controller'] = $controller;
        }
        if(!empty($action)){
            $params['action'] = $action;
        }
        $this->routes[$subAppName][$lang][$routeName] = array(
            'rule' =>        $rule,
            'regexp' =>        $regexp,
            'keywords' =>    $keywords,
            'params' =>        $params,
            /*'projectType' =>    $projectType,
            'projectName' =>    $projectName,
            'controller' =>    $controller,
            'action' =>    $action,*/
        );
    }
	
    /**
     * @param string $projectType
     * @param string $projectName
     * @param string $controller
     * @param string $action
     * @return string
     */
    public function getRouteName($projectType, $projectName, $controller = null, $action = null) {
        $name = strtolower($projectType);
        if(!empty($projectName)){
            $name .= '_' . $projectName;
        }
        if(!empty($controller)){
            $name .= '_' . $controller;
            if(!empty($action)){
                $name .= '_' . $action;
            }
        }
        return $name;
	}
	
    /**
     * @param \muuska\url\UrlCreationInput $input
     * @throws \Exception
     * @return string
     */
    public function createUrl(\muuska\url\UrlCreationInput $input)
    {
        $subAppName = $input->getSubAppName();
        $lang = $input->getLang();
        $controllerName = $input->getControllerName();
        $action = $input->getAction();
        $params = $input->getParams();
        $mode = $input->getMode();
        
        $subAppConfig = App::getApp()->getSubApplicationConfig($subAppName);
        if($subAppConfig === null){
            throw new \Exception(sprintf('%s Application not found', $subAppName));
        }
        if(!isset($this->routes[$subAppName]) || !isset($this->routes[$subAppName][$lang])){
            $this->loadRoutes($subAppName, $lang);
		}
		$routes = $this->routes[$subAppName][$lang];
		$params['controller'] = $controllerName;
		if(!empty($action) && ($action !== ActionCode::DEFAULT_PROCESS)){
		    $params['action'] = $action;
		}
		if(!isset($params['action']) && ($controllerName === Names::HOME_CONTROLLER)){
		    $params['controller'] = '';
		}
		$params['projectType'] = $input->getProjectType();
		$projectName = $input->getProjectName();
		if(empty($params['projectType']) || ($params['projectType'] === ProjectType::FRAMEWORK) || ($params['projectType'] === ProjectType::APPLICATION)){
		    $params['projectType'] = ProjectType::APPLICATION;
		}else{
		    $params['projectName'] = $projectName;
		}
		
		$routeName = $this->getRouteName($params['projectType'], $projectName, $controllerName, $action);
		if(!isset($routes[$routeName])){
		    $routeName = $this->getRouteName($params['projectType'], $projectName, $controllerName, null);
		    if(!isset($routes[$routeName])){
		        $routeName = $this->getRouteName($params['projectType'], $projectName, null, null);
		        if(!isset($routes[$routeName])){
		            $routeName = $this->getRouteName($params['projectType'], null, null, null);
		        }
		    }
		}
		$finalUrl = App::getApp()->getBaseUrl();
		$subAppCount = count(App::getApp()->getEnabledSubApplications());
		if($subAppCount > 1){
		    $host = $subAppConfig->getString('host');
		    if(empty($host)){
		        $urlPath = $subAppConfig->getString('url_path');
		        $finalUrl .= empty($urlPath) ? '' : $urlPath.'/';
		    }else{
		        $finalUrl = $host.'/';
		    }
		}
		if($this->multilingualEnabled && $subAppConfig->getBool('lang_in_url')){
		    $finalUrl .= $lang.'/';
		}
		
		if (!isset($routes[$routeName])) {
            $query = http_build_query($params, '', '&');
            $finalUrl .= ($this->rewritingEnabled ? '' : 'index.php') . (empty($query) ? '' : '?' . $query).$input->getAnchor();
            return $finalUrl;
        }
        $route = $routes[$routeName];
        // Check required fields
        $queryParams = isset($route['params']) ? $route['params'] : array();
        foreach ($route['keywords'] as $key => $data) {
            if ($data['required']) {
                if (!array_key_exists($key, $params)) {
                    throw new \Exception('Router::createUrl() miss required parameter "'.$key.'" for route "'.$routeName.'"');
				}
				if (isset($this->defaultRoutes[$routeName])) {
				    $queryParams[$this->defaultRoutes[$routeName]['keywords'][$key]['param']] = $params[$key];
				}
            }
        }
        // Build an url which match a route
        if ((empty($mode) && $this->rewritingEnabled) || ($mode == UrlCreationMode::REWRITE)) {
            $url = $route['rule'];
            $addParam = array();
            foreach ($params as $key => $value) {
                if (!isset($route['keywords'][$key])) {
                    if (!isset($this->defaultRoutes[$routeName]['keywords'][$key]) && !isset($route['params'][$key])) {
                        $addParam[$key] = $value;
                    }
                } else {
                    if ($params[$key]) {
                        $replace = $route['keywords'][$key]['prepend'].$params[$key].$route['keywords'][$key]['append'];
                    } else {
                        $replace = '';
                    }
                    $url = preg_replace('#\{([^{}]*:)?'.$key.'(:[^{}]*)?\}#', $replace, $url);
                }
            }
            $url = preg_replace('#\{([^{}]*:)?[a-z0-9_]+?(:[^{}]*)?\}#', '', $url);
            if (count($addParam)) {
                $url .= '?'.http_build_query($addParam, '', '&');
            }
        }
        // Build a classic url index.php?controller=foo&...
        else {
            $addParams = array();
            foreach ($params as $key => $value) {
                if (!isset($route['keywords'][$key]) && !isset($this->defaultRoutes[$routeName]['keywords'][$key])) {
                    $addParams[$key] = $value;
                }
            }
            $query = http_build_query(array_merge($addParams, $queryParams), '', '&');
            /*if ($this->multilingualEnabled) {
                $query .= (!empty($query) ? '&' : '').'lang='.$lang;
            }*/
            $url = 'index.php?'.$query;
        }
        $finalUrl .= ((strpos($url, '?')===0) ? 'index.php' : '') . $url.$input->getAnchor();
        return $finalUrl;
    }
}