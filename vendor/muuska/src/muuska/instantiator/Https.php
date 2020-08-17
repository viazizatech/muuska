<?php
namespace muuska\instantiator;

class Https
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Https
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
    /**
     * @param string $type
     * @param string $url
     * @param int $statusCode
     * @return \muuska\http\redirection\DirectRedirection
     */
    public function createDirectRedirection($type, $url, $statusCode = null) {
	    return new \muuska\http\redirection\DirectRedirection($type, $url, $statusCode);
	}
	
	/**
	 * @param \muuska\http\Response $response
	 * @param \muuska\url\ControllerUrlCreator $controllerUrlCreator
	 * @param \muuska\http\VisitorInfoRecorder $visitorInfoRecorder
	 * @param bool $alertInfoRecordingEnabled
	 * @param string $alertRecorderKey
	 * @param bool $backInfoRecordingEnabled
	 * @param string $backRecorderKey
	 * @return \muuska\http\redirection\RedirectionInput
	 */
	public function createRedirectionInput(\muuska\http\Response $response, \muuska\url\ControllerUrlCreator $controllerUrlCreator, \muuska\http\VisitorInfoRecorder $visitorInfoRecorder = null, $alertInfoRecordingEnabled = false, $alertRecorderKey = null, $backInfoRecordingEnabled = false, $backRecorderKey = null) {
        return new \muuska\http\redirection\RedirectionInput($response, $controllerUrlCreator, $visitorInfoRecorder, $alertInfoRecordingEnabled, $alertRecorderKey, $backInfoRecordingEnabled, $backRecorderKey);
	}
	
	/**
	 * @param string $type
	 * @param string $controllerName
	 * @param string $action
	 * @param array $params
	 * @param string $successCode
	 * @param string $errorCode
	 * @param \muuska\http\redirection\Redirection $backRedirection
	 * @param int $statusCode
	 * @return \muuska\http\redirection\DynamicRedirection
	 */
	public function createDynamicRedirection($type, $controllerName = null, $action = null, $params = array(), $successCode = null, $errorCode = null, \muuska\http\redirection\Redirection $backRedirection = null, $statusCode = null) {
	    return new \muuska\http\redirection\DynamicRedirection($type, $controllerName, $action, $params, $successCode, $errorCode, $backRedirection, $statusCode);
	}
	
	/**
	 * @param string $protocol
	 * @param string $scheme
	 * @param bool $secure
	 * @param string $method
	 * @param array $queryParams
	 * @param array $postParams
	 * @param array $files
	 * @param array $headers
	 * @param \muuska\http\Cookie[] $cookies
	 * @param string $remoteAddr
	 * @param string $remoteHost
	 * @param string $remotePort
	 * @param string $serverAddr
	 * @param string $serverName
	 * @param int $serverPort
	 * @param string $contextPath
	 * @param string $pathInfo
	 * @param string $requestURI
	 * @param string $queryString
	 * @param string $requestedSessionId
	 * @param int $requestedSessionIdSource
	 * @param string $contentType
	 * @param string $characterEncoding
	 * @param int $contentLength
	 * @param string[] $locales
	 * @param string $locale
	 * @param string $localAddr
	 * @param string $localName
	 * @param int $localPort
	 * @return \muuska\http\Request
	 */
	public function createRequest($protocol, $scheme, $secure, $method, $queryParams, $postParams, $files, $headers, $cookies,
	    $remoteAddr, $remoteHost, $remotePort, $serverAddr, $serverName, $serverPort,
	    $contextPath, $pathInfo, $requestURI, $queryString, $requestedSessionId, $requestedSessionIdSource,
	    $contentType, $characterEncoding, $contentLength, $locales, $locale, $localAddr = null, $localName = null, $localPort = null) 
	{
        return new \muuska\http\Request($protocol, $scheme, $secure, $method, $queryParams, $postParams, $files, $headers, $cookies,
            $remoteAddr, $remoteHost, $remotePort, $serverAddr, $serverName, $serverPort,
            $contextPath, $pathInfo, $requestURI, $queryString, $requestedSessionId, $requestedSessionIdSource,
            $contentType, $characterEncoding, $contentLength, $locales, $locale, $localAddr, $localName, $localPort);
	}
	
    /**
     * @param string $protocol
     * @param \muuska\http\Cookie[] $cookies
     * @param array $headers
     * @return \muuska\http\Response
     */
    public function createResponse($protocol, $cookies = array(), $headers = array()) {
	    return new \muuska\http\Response($protocol, $cookies, $headers);
	}
	
	/**
	 * @param \muuska\dao\DAOFactory $daoFactory
	 * @return \muuska\http\Router
	 */
	public function createRouter(\muuska\dao\DAOFactory $daoFactory) {
	    return new \muuska\http\Router($daoFactory);
	}
	
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
	 * @return \muuska\http\event\RequestParsingEvent
	 */
	public function createRequestParsingEvent(\muuska\http\Router $source, \muuska\http\Request $request, \muuska\http\Response $response, $finalPathInfo = null, $subAppName = null, $lang = null, $projectType = null, $projectName = null, $controller = null, $action = null, $pathParams = array(), $variationTriggers = array(), $params = array()) {
	    return new \muuska\http\event\RequestParsingEvent($source, $request, $response, $finalPathInfo, $subAppName, $lang, $projectType, $projectName, $controller, $action, $pathParams, $variationTriggers, $params);
	}
	
	/**
	 * @param \muuska\http\Router $source
	 * @param string $subAppName
	 * @param string $lang
	 * @param array $params
	 * @return \muuska\http\event\RouteLoadingEvent
	 */
	public function createRouteLoadingEvent(\muuska\http\Router $source, $subAppName, $lang, $params = array()) {
	    return new \muuska\http\event\RouteLoadingEvent($source, $subAppName, $lang, $params);
	}
	
	/**
	 * @param \muuska\http\Response $source
	 * @param array $params
	 * @return \muuska\http\event\ResponseSendingEvent
	 */
	public function createResponseSendingEvent(\muuska\http\Response $source, $params = array()) {
	    return new \muuska\http\event\ResponseSendingEvent($source, $params);
	}
	
	/**
	 * @param callable $callback
	 * @param array $initialParams
	 * @return \muuska\http\event\DefaultResponseSendingListener
	 */
	public function createDefaultResponseSendingListener($callback, $initialParams = null) {
	    return new \muuska\http\event\DefaultResponseSendingListener($callback, $initialParams);
	}
	
	/**
	 * @param string                        $name     The name of the cookie
     * @param string|null                   $value    The value of the cookie
     * @param int|string|\DateTimeInterface $expire   The time the cookie expires
     * @param string                        $path     The path on the server in which the cookie will be available on
     * @param string|null                   $domain   The domain that the cookie is available to
     * @param bool                          $secure   Whether the cookie should only be transmitted over a secure HTTPS connection from the client
     * @param bool                          $httpOnly Whether the cookie will be made accessible only through the HTTP protocol
     * @param bool                          $raw      Whether the cookie value should be sent with no url encoding
     * @param string|null                   $sameSite Whether the cookie will be available for cross-site requests
	 * @return \muuska\http\Cookie
	 */
	public function createCookie($name, $value = null, $expire = 0, $path = '/', $domain = null, $secure = false, $httpOnly = true, $raw = false, $sameSite = null) {
	    return new \muuska\http\Cookie($name, $value, $expire, $path, $domain, $secure, $httpOnly, $raw, $sameSite);
	}
	
	/**
	 * @param \muuska\http\Request $request
	 * @param \muuska\http\Response $response
	 * @param string $name
	 * @param string $path
	 * @param int $expire
	 * @param boolean $cipherAlgorithm
	 * @param array $shared_urls
	 * @param boolean $standalone
	 * @param boolean $secure
	 * @return \muuska\http\CookieVisitorInfoRecorder
	 */
	public function createCookieVisitorInfoRecorder(\muuska\http\Request $request, \muuska\http\Response $response, $name, $path = '', $expire = null, $cipherAlgorithm = false, $shared_urls = null, $standalone = false, $secure = false) {
	    return new \muuska\http\CookieVisitorInfoRecorder($request, $response, $name, $path, $expire, $cipherAlgorithm, $shared_urls, $standalone, $secure);
	}
	
	/**
	 * @param string $id
	 * @return \muuska\http\session\NativeSession
	 */
	public function createNativeSession($id = null) {
	    return new \muuska\http\session\NativeSession($id);
	}

	/**
	 * @return \muuska\http\Request
	 */
	public function createRequestFromGlobal() {
	    return \muuska\http\Request::createFromGlobal();
	}
}
