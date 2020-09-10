<?php
namespace muuska\http;

use muuska\util\App;

class Request
{
    const REQUESTED_SESSION_SOURCE_COOKIE = 1;
    const REQUESTED_SESSION_SOURCE_URL = 2;
    
    /**
     * @var string
     */
    protected $protocol;
    
    /**
     * @var array
     */
    protected $headers;
    
    /**
     * @var array
     */
    protected $cookies;
    
    /**
     * @var string
     */
    protected $characterEncoding;
    
    /**
     * @var string
     */
    protected $contentType;
    
    /**
     * @var int
     */
    protected $contentLength;
    
    /**
     * @var string
     */
    protected $locale;
    
    /**
     * @var string
     */
    protected $method;
    
    /**
     * @var array
     */
    protected $queryParams;
    
    /**
     * @var array
     */
    protected $postParams;
    
    /**
     * @var string
     */
    protected $files;
    
    /**
     * @var string
     */
    protected $localAddr;
    
    /**
     * @var string
     */
    protected $localName;
    
    /**
     * @var int
     */
    protected $localPort;
    
    /**
     * @var string
     */
    protected $remoteAddr;
    
    /**
     * @var string
     */
    protected $remoteHost;
    
    /**
     * @var int
     */
    protected $remotePort;
    
    /**
     * @var string
     */
    protected $serverAddr;
    
    /**
     * @var string
     */
    protected $serverName;
    
    /**
     * @var int
     */
    protected $serverPort;
    
    /**
     * @var string
     */
    protected $scheme;
    
    /**
     * @var string
     */
    protected $pathInfo;
    
    /**
     * @var string
     */
    protected $contextPath;
    
    /**
     * @var string
     */
    protected $requestURI;
    
    /**
     * @var string
     */
    protected $queryString;
    
    /**
     * @var string
     */
    protected $requestedSessionId;
    
    /**
     * @var \muuska\http\session\Session
     */
    protected $session;
    
    /**
     * @var array
     */
    protected $attributes = array();
    
    /**
     * @var bool
     */
    protected $secure;
    
    /**
     * @var array
     */
    protected $locales;
    
    /**
     * @var int
     */
    protected $requestedSessionIdSource;
    
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
     */
    public function __construct($protocol, $scheme, $secure, $method, $queryParams, $postParams, $files, $headers, $cookies,
        $remoteAddr, $remoteHost, $remotePort, $serverAddr, $serverName, $serverPort,
        $contextPath, $pathInfo, $requestURI, $queryString, $requestedSessionId, $requestedSessionIdSource,
        $contentType, $characterEncoding, $contentLength, $locales, $locale, $localAddr = null, $localName = null, $localPort = null
        ) 
    {
        $this->protocol = $protocol;
        $this->scheme = $scheme;
        $this->secure = $secure;
        $this->method = $method;
        $this->queryParams = $queryParams;
        $this->postParams = $postParams;
        $this->files = $files;
        $this->headers = $headers;
        $this->cookies = $cookies;
        $this->remoteAddr = $remoteAddr;
        $this->remoteHost = $remoteHost;
        $this->remotePort = $remotePort;
        $this->serverAddr = $serverAddr;
        $this->serverName = $serverName;
        $this->serverPort = $serverPort;
        $this->contextPath = $contextPath;
        $this->pathInfo = $pathInfo;
        $this->requestURI = $requestURI;
        $this->queryString = $queryString;
        $this->requestedSessionId = $requestedSessionId;
        $this->requestedSessionIdSource = $requestedSessionIdSource;
        $this->contentType = $contentType;
        $this->characterEncoding = $characterEncoding;
        $this->contentLength = $contentLength;
        $this->locales = $locales;
        $this->locale = $locale;
        $this->localAddr = $localAddr;
        $this->localName = $localName;
        $this->localPort = $localPort;
    }
    
    
    /**
     * @return Request
     */
    public static function createFromGlobal(){
        $cookies = array();
        if(is_array($_COOKIE)){
            foreach ($_COOKIE as $key => $value) {
                $cookies[$key] = App::https()->createCookie($key, $value);
            }
        }
        $requestURI = static::getUri();
        $posInterogation = strpos($requestURI, '?');
        if($posInterogation !== false){
            $requestURI = substr($requestURI, 0, $posInterogation);
        }
        
        $contentType = null;
        $contentLength = null;
        $characterEncoding = null;
        if ('cli-server' === \PHP_SAPI) {
            if(isset($_SERVER['HTTP_CONTENT_LENGTH'])){
                $contentLength = $_SERVER['HTTP_CONTENT_LENGTH'];
            }
            if(isset($_SERVER['HTTP_CONTENT_TYPE'])){
                $contentType = $_SERVER['HTTP_CONTENT_TYPE'];
            }
        }else{
            if(isset($_SERVER['CONTENT_LENGTH'])){
                $contentLength = $_SERVER['CONTENT_LENGTH'];
            }
            if(isset($_SERVER['CONTENT_TYPE'])){
                $contentType = $_SERVER['CONTENT_TYPE'];
            }
        }
        $locales = static::getLanguages();
        $locale = isset($locales[0]) ? $locales[0] : null;
        
        $contextPath = '';
        if(isset($_SERVER['SCRIPT_NAME']) && !empty($_SERVER['SCRIPT_NAME'])){
            $contextPath = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
        }
        $pathInfo = null;
        if(!empty($contextPath)) {
            $pathInfo = substr($requestURI, strlen($contextPath));
        }  else {
            $pathInfo = $requestURI;
        }
        if($pathInfo === '/') {
            $pathInfo = null;
        }
        $requestedSessionId = null;
        $requestedSessionIdSource = null;
        $sessionName = session_name();
        if(isset($_COOKIE[$sessionName])){
            $requestedSessionId = $_COOKIE[$sessionName];
            $requestedSessionIdSource = self::REQUESTED_SESSION_SOURCE_COOKIE;
        }elseif(isset($_GET[$sessionName])){
            $requestedSessionId = $_GET[$sessionName];
            $requestedSessionIdSource = self::REQUESTED_SESSION_SOURCE_URL;
        }
        
        $request = new static($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_SCHEME'], static::isSecureMode(), $_SERVER['REQUEST_METHOD'], $_GET, $_POST, $_FILES, getallheaders(), $cookies,
            $_SERVER['REMOTE_ADDR'], null, $_SERVER['REMOTE_PORT'], $_SERVER['SERVER_ADDR'], static::getServerHost(), $_SERVER['SERVER_PORT'],
            $contextPath, $pathInfo, $requestURI, $_SERVER['QUERY_STRING'], $requestedSessionId, $requestedSessionIdSource,
            $contentType, $characterEncoding, $contentLength, $locales, $locale);
        return $request;
    }
    
    protected static function getUri(){
        $uri = '';
        if (isset($_SERVER['REQUEST_URI'])) {
            $uri = $_SERVER['REQUEST_URI'];
        } elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
            $uri = $_SERVER['HTTP_X_REWRITE_URL'];
        }
        return $uri;
    }
    
    protected static function isSecureMode()
    {
        if (isset($_SERVER['HTTPS'])) {
            return in_array(strtolower($_SERVER['HTTPS']), array(1, 'on'));
        }
        // $_SERVER['SSL'] exists only in some specific configuration
        if (isset($_SERVER['SSL'])) {
            return in_array(strtolower($_SERVER['SSL']), array(1, 'on'));
        }
        // $_SERVER['REDIRECT_HTTPS'] exists only in some specific configuration
        if (isset($_SERVER['REDIRECT_HTTPS'])) {
            return in_array(strtolower($_SERVER['REDIRECT_HTTPS']), array(1, 'on'));
        }
        if (isset($_SERVER['HTTP_SSL'])) {
            return in_array(strtolower($_SERVER['HTTP_SSL']), array(1, 'on'));
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            return strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https';
        }
        
        return false;
    }
    
    protected static function getServerHost(){
        $host = (isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST']);
        return $host;
    }
    
    protected static function getLanguages(){
        $result = array();
        $languages = (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) ? explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']) : array();
        foreach ($languages as $lang) {
            if (false !== strpos($lang, '-')) {
                $codes = explode('-', $lang);
                if ('i' === $codes[0]) {
                    // Language not listed in ISO 639 that are not variants
                    // of any listed language, which can be registered with the
                    // i-prefix, such as i-cherokee
                    if (\count($codes) > 1) {
                        $lang = $codes[1];
                    }
                } else {
                    $max = \count($codes);
                    for ($i = 0 ; $i < $max; ++$i) {
                        if (0 === $i) {
                            $lang = strtolower($codes[0]);
                        } else {
                            $lang .= '_'.strtoupper($codes[$i]);
                        }
                    }
                }
            }
            
            $result[] = $lang;
        }
        
        return $result;
    }
	
    /**
     * @return array
     */
    public function getFiles(){
        return $this->files;
    }
    
    /**
     * @param string $name
     * @return array
     */
    public function getFile($name){
        return isset($this->files[$name]) ? $this->files[$name] : null;
    }
    
    /**
     * @param string $name
     * @return bool
     */
    public function hasFile($name){
        return isset($this->files[$name]);
    }
    
    /**
     * Returns an array containing all of the Cookie objects the client sent with this request.
     * 
     * @return array
     */
    public function getCookies(){
        return $this->cookies;
    }
    
    /**
     * Returns a specified Cookie or null if not exist.
     *
     * @return Cookie
     */
    public function getCookie($name){
        return isset($this->cookies[$name]) ? $this->cookies[$name] : null;
    }
    
    /**
     * Returns the value of a specified Cookie.
     *
     * @return string
     */
    public function getCookieValue($name){
        $value = null;
        $cookie = $this->getCookie($name);
        if($cookie !== null){
            $value = $cookie->getValue();
        }
        return $value;
    }
    
    /**
     * @param string $name
     * @return bool
     */
    public function containsCookie($name){
        return isset($this->cookies[$name]);
    }
    
    
    /**
     * Returns the value of the specified request header as a long value that represents a Date object.
     * 
     * @param string $name
     * @return int
     */
    public function getDateHeader($name){
        return $this->getHeader($name);
    }
    
    /**
     * @return string
     */
    public function getUserAgent(){
        return $this->getHeader('User-Agent');
    }
    
    /**
     * @param string $name
     * @return bool
     */
    public function containsHeader($name){
        return isset($this->headers[$name]);
    }
    
    /**
     * Returns the value of the specified request header as a String.
     * 
     * @param string $name
     * @return string
     */
    public function getHeader($name){
        return isset($this->headers[$name]) ? $this->headers[$name] : null;
    }
    
    /**
     * Returns all the values of the specified request header as an array of String.
     * 
     * @return array
     */
    public function getHeaders(){
        return $this->headers;
    }
    
    /**
     * Returns an array of all the header names this request contains.
     * 
     * @return array
     */
    public function getHeaderNames(){
        return array_keys($this->headers);
    }
    
    /**
     * Returns the name of the HTTP method with which this request was made, for example, GET, POST, or PUT.
     * 
     * @return string
     */
    public function getMethod(){
        return $this->method;
    }
    
    /**
     * Returns any extra path information associated with the URL the client sent when it made this request.
     * 
     * @return string
     */
    public function getPathInfo(){
        return $this->pathInfo;
    }
    
    /**
     * Returns the portion of the request URI that indicates the context of the request.
     * 
     * @return string
     */
    public function getContextPath(){
        return $this->contextPath;
    }
    
    /**
     * Returns the query string that is contained in the request URL after the path.
     * @return string
     */
    public function getQueryString(){
        return $this->queryString;
    }
    
    
    
    /**
     * Returns the part of this request's URL from the protocol name up to the query string in the first line of the HTTP request.
     * 
     * @return string
     */
    public function getRequestURI(){
        return $this->requestURI;
    }
    
    /**
     * Reconstructs the URL the client used to make the request.
     * 
     * @param bool $addScheme
     * @param bool $addQueryString
     * @return string
     */
    public function getRequestURL($addScheme = true, $addQueryString = false){
        $url = $addScheme ? $this->getScheme() . '://' : '';
        $url .= $this->getServerName().$this->getRequestURI();
        if($addQueryString){
            $queryString = $this->getQueryString();
            $url .= empty($queryString) ? '' : '?' . $queryString;
        }
        return $url;
    }
    
    /**
     * Returns the current HttpSession associated with this request or, if there is no current session and create is true, returns a new session.
     * 
     * @param bool $createIfNotExist
     * @return \muuska\http\session\Session
     */
    public function getSession($createIfNotExist = false){
        if($createIfNotExist && ($this->session === null)){
            $this->session = App::getApp()->createDefaultSession($this->getRequestedSessionId());
        }
        return $this->session;
    }
    
    /**
     * @return string
     */
    public function getRequestedSessionId(){
        return $this->requestedSessionId;
    }
    
    /**
     * Checks whether the requested session ID came in as a cookie.
     * 
     * @return bool
     */
    public function isRequestedSessionIdFromCookie(){
        return ($this->requestedSessionIdSource === self::REQUESTED_SESSION_SOURCE_COOKIE);
    }
    
    /**
     * Checks whether the requested session ID came in as part of the request URL.
     * 
     * @return bool
     */
    public function isRequestedSessionIdFromURL(){
        return ($this->requestedSessionIdSource === self::REQUESTED_SESSION_SOURCE_URL);
    }
    
    /**
     * Checks whether the requested session ID is still valid.
     * 
     * @return bool
     */
    public function isRequestedSessionIdValid(){
        return true;
    }
    
    /**
     * Returns the preferred Locale that the client will accept content in, based on the Accept-Language header.
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
    
    /**
     * Returns an array of locales indicating, in decreasing order starting with the preferred locale, the locales that are acceptable to the client based on the Accept-Language header.
     * 
     * @return array
     */
    public function getLocales(){
        return $this->locales;
    }
    
    /**
     * Returns the name and version of the protocol the request uses in the form protocol/majorVersion.minorVersion, for example, HTTP/1.1.
     * 
     * @return string
     */
    public function getProtocol(){
        return $this->protocol;
    }
    
    /**
     * Returns the Internet Protocol (IP) address of the interface on which the request was received.
     * 
     * @return string
     */
    public function getLocalAddr(){
        return $this->localAddr;
    }
    
    /**
     * Returns the host name of the Internet Protocol (IP) interface on which the request was received.
     * 
     * @return string
     */
    public function getLocalName(){
        return $this->localName;
    }
    
    /**
     * Returns the Internet Protocol (IP) port number of the interface on which the request was received.
     * 
     * @return int
     */
    public function getLocalPort(){
        return $this->localPort;
    }
    
    
    /**
     * Returns the Internet Protocol (IP) address of the client or last proxy that sent the request.
     * 
     * @return string
     */
    public function getRemoteAddr(){
        return $this->remoteAddr;
    }
    
    /**
     * Returns the fully qualified name of the client or the last proxy that sent the request.
     * 
     * @return string
     */
    public function getRemoteHost(){
        return $this->remoteHost;
    }
    
    /**
     * Returns the Internet Protocol (IP) source port of the client or last proxy that sent the request.
     * 
     * @return int
     */
    public function getRemotePort(){
        return $this->remotePort;
    }
    
    /**
     * Returnsthe Internet Protocol (IP) address of the server to which the request was sent.
     * 
     * @return string
     */
    public function getServerAddr(){
        return $this->serverAddr;
    }
    
    /**
     * Returns the host name of the server to which the request was sent.
     * 
     * @return string
     */
    public function getServerName(){
        return $this->serverName;
    }
    
    /**
     * Returns the port number to which the request was sent.
     * 
     * @return int
     */
    public function getServerPort(){
        return $this->serverPort;
    }
    
    /**
     * Returns the name of the scheme used to make this request, for example, http, https, or ftp.
     * 
     * @return string
     */
    public function getScheme(){
        return $this->scheme;
    }
    
    /**
     * Returns a boolean indicating whether this request was made using a secure channel, such as HTTPS.
     * 
     * @return bool
     */
    public function isSecure(){
        return $this->secure;
    }
    
    /**
     * Returns the value of the named attribute as an Object, or null if no attribute of the given name exists.
     * 
     * @param string $name
     * @return mixed
     */
    public function getAttribute($name){
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }
    
    /**
     * Removes an attribute from this request.
     * 
     * @param string $name
     */
    public function removeAttribute($name){
        unset($this->attributes[$name]);
    }
    
    /**
     * Returns an array containing all of the attributes available to this request.
     * 
     * @return array
     */
    public function getAttributes(){
        return $this->attributes;
    }
    
    /**
     * Returns an Enumeration containing the names of the attributes available to this request.
     * 
     * @return array
     */
    public function getAttributeNames(){
        return array_keys($this->attributes);
    }
    
    /**
     * Stores an attribute in this request.
     * 
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value){
        $this->attributes[$name] = $value;
    }
    
    /**
     * Returns the name of the character encoding used in the body of this request.
     * 
     * @return string
     */
    public function getCharacterEncoding()
    {
        return $this->characterEncoding;
    }
    
    /**
     * Overrides the name of the character encoding used in the body of this request.
     *
     * @param string $characterEncoding
     */
    public function setCharacterEncoding($characterEncoding)
    {
        $this->characterEncoding = $characterEncoding;
    }

    /**
     * Returns the MIME type of the body of the request, or null if the type is not known.
     * 
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Returns the length, in bytes, of the request body and made available by the input stream, or -1 if the length is not known.
     * 
     * @return int
     */
    public function getContentLength()
    {
        return $this->contentLength;
    }

    /**
     * @return array
     */
    public function getQueryParams()
    {
        return $this->queryParams;
    }
    
    /**
     * @return array
     */
    public function getPostParams()
    {
        return $this->postParams;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasPostParam($name)
    {
        return array_key_exists($name, $this->postParams);
    }
    
    /**
     * @param string $name
     * @return array
     */
    public function hasQueryParam($name)
    {
        return array_key_exists($name, $this->queryParams);
    }
    
    /**
     * @param string $name
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getPostParam($name, $defaultValue = null)
    {
        return isset($this->postParams[$name]) ? $this->cleanValue($this->postParams[$name]) : $defaultValue;
    }
    
    /**
     * @param string $name
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getQueryParam($name, $defaultValue = null)
    {
        return isset($this->queryParams[$name]) ? $this->cleanValue($this->queryParams[$name]) : $defaultValue;
    }
    
    protected function cleanValue($value)
    {
        if(is_array($value)){
            $result = array();
            foreach($value as $key => $val){
                $result[$key] = $this->cleanValue($val);
            }
        }else{
            $result = stripslashes(urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($value))));
        }
        return $result;
    }
    
    /**
     * @param string $name
     * @param mixed $defaultValue
     * @return mixed
     */
    public function getParam($name, $defaultValue = null)
    {
        $value = $defaultValue;
        if($this->hasPostParam($name)){
            $value = $this->getPostParam($name, $defaultValue);
        }elseif($this->hasQueryParam($name)){
            $value = $this->getQueryParam($name, $defaultValue);
        }
        return $value;
    }
    
    /**
     * @return string
     */
    public function getBody()
    {
        return file_get_contents("php://input");
    }
}
