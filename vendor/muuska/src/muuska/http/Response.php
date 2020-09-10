<?php
namespace muuska\http;

use muuska\util\App;

class Response
{
    const STATUS_CODE_CUSTOM = 0;
    const STATUS_CODE_100 = 100;
    const STATUS_CODE_101 = 101;
    const STATUS_CODE_102 = 102;
    const STATUS_CODE_200 = 200;
    const STATUS_CODE_201 = 201;
    const STATUS_CODE_202 = 202;
    const STATUS_CODE_203 = 203;
    const STATUS_CODE_204 = 204;
    const STATUS_CODE_205 = 205;
    const STATUS_CODE_206 = 206;
    const STATUS_CODE_207 = 207;
    const STATUS_CODE_208 = 208;
    const STATUS_CODE_226 = 226;
    const STATUS_CODE_300 = 300;
    const STATUS_CODE_301 = 301;
    const STATUS_CODE_302 = 302;
    const STATUS_CODE_303 = 303;
    const STATUS_CODE_304 = 304;
    const STATUS_CODE_305 = 305;
    const STATUS_CODE_306 = 306;
    const STATUS_CODE_307 = 307;
    const STATUS_CODE_308 = 308;
    const STATUS_CODE_400 = 400;
    const STATUS_CODE_401 = 401;
    const STATUS_CODE_402 = 402;
    const STATUS_CODE_403 = 403;
    const STATUS_CODE_404 = 404;
    const STATUS_CODE_405 = 405;
    const STATUS_CODE_406 = 406;
    const STATUS_CODE_407 = 407;
    const STATUS_CODE_408 = 408;
    const STATUS_CODE_409 = 409;
    const STATUS_CODE_410 = 410;
    const STATUS_CODE_411 = 411;
    const STATUS_CODE_412 = 412;
    const STATUS_CODE_413 = 413;
    const STATUS_CODE_414 = 414;
    const STATUS_CODE_415 = 415;
    const STATUS_CODE_416 = 416;
    const STATUS_CODE_417 = 417;
    const STATUS_CODE_418 = 418;
    const STATUS_CODE_422 = 422;
    const STATUS_CODE_423 = 423;
    const STATUS_CODE_424 = 424;
    const STATUS_CODE_425 = 425;
    const STATUS_CODE_426 = 426;
    const STATUS_CODE_428 = 428;
    const STATUS_CODE_429 = 429;
    const STATUS_CODE_431 = 431;
    const STATUS_CODE_451 = 451;
    const STATUS_CODE_444 = 444;
    const STATUS_CODE_499 = 499;
    const STATUS_CODE_500 = 500;
    const STATUS_CODE_501 = 501;
    const STATUS_CODE_502 = 502;
    const STATUS_CODE_503 = 503;
    const STATUS_CODE_504 = 504;
    const STATUS_CODE_505 = 505;
    const STATUS_CODE_506 = 506;
    const STATUS_CODE_507 = 507;
    const STATUS_CODE_508 = 508;
    const STATUS_CODE_510 = 510;
    const STATUS_CODE_511 = 511;
    const STATUS_CODE_599 = 599;
    
    /**
     * @var array Recommended Reason Phrases
     */
    protected static $statusMessages = array(
        // INFORMATIONAL CODES
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        // SUCCESS CODES
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        226 => 'IM Used',
        // REDIRECTION CODES
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy', // Deprecated
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        // CLIENT ERROR
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Too Early',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        444 => 'Connection Closed Without Response',
        451 => 'Unavailable For Legal Reasons',
        499 => 'Client Closed Request',
        // SERVER ERROR
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
        599 => 'Network Connect Timeout Error',
    );
    
    /**
     * @var string
     */
    protected $protocol;
    
    /**
     * @var int Status code
     */
    protected $status = 200;
    
    /**
     * @var array
     */
    protected $headers;
    
    /**
     * @var Cookie[]
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
    protected $body;
    
    /**
     * @var string
     */
    protected $locale;
    
    /**
     * @var \muuska\http\event\ResponseSendingListener[]
     */
    protected $sendingListeners = array();
    
    /**
     * @param string $protocol
     * @param Cookie[] $cookies
     * @param array $headers
     */
    public function __construct($protocol, $cookies = array(), $headers = array()){
        $this->protocol = $protocol;
        $this->cookies = $cookies;
        $this->headers = $headers;
    }
    
    /**
     * @param string $name
     * @return string
     */
    public function getHeader($name) {
        return isset($this->headers[$name]) ? $this->headers[$name] : null;
    }
    
    /**
     * Returns a boolean indicating whether the named response header has already been set.
     * 
     * @param string $name
     * @return bool
     */
    public function containsHeader($name) {
        return isset($this->headers[$name]);
    }
    
    /**
     * Adds a response header with the given name and value.
     * 
     * @param string $name
     * @param string $value
     */
    public function addHeader($name, $value) {
        $this->setHeader($name, $value);
    }
    
    /**
     * Sets a response header with the given name and value.
     * 
     * @param string $name
     * @param string $value
     */
    public function setHeader($name, $value) {
        $this->headers[$name] = $value;
    }
    
    /**
     * @param string $name
     */
    public function removeHeader($name) {
        unset($this->headers[$name]);
    }
    
    /**
     *  Adds a response header with the given name and date-value.
     *  
     * @param string $name
     * @param int $value
     */
    public function addDateHeader($name, $value) {
        $this->setDateHeader($name, $value);
    }
    
    /**
     * Sets a response header with the given name and date-value.
     * 
     * @param string $name
     * @param int $value
     */
    public function setDateHeader($name, $value) {
        $date = new \DateTime($value);
        $date->setTimezone(new \DateTimeZone('UTC'));
        $this->setHeader($name, $date->format('D, d M Y H:i:s').' GMT');
    }
    
    /**
     * @param int $value
     */
    public function setDate($value) {
        $this->setDateHeader('Date', $value);
    }
    
    /**
     * @return string
     */
    public function getDate() {
        return $this->getHeader('Date');
    }
    
    /**
     * Clears any data that exists in the buffer as well as the status code and headers.
     */
    public function reset() {
        $this->clearHeaders();
        $this->clearCookies();
        $this->clearBody();
        $this->contentType = null;
        $this->contentLength = null;
        $this->characterEncoding = null;
        $this->status = self::STATUS_CODE_200;
        $this->statusMessagge = null;
        $this->locale = null;
    }
    
    /**
     * @param string $body
     */
    public function appendBody($body)
    {
        if($this->body === null){
            $this->body = $body;
        }else{
            $this->body .= $body;
        }
    }
    
    public function clearBody()
    {
        $this->body = null;
    }
    
    public function clearHeaders()
    {
        $this->headers = array();
    }
    
    public function clearCookies()
    {
        $this->cookies = array();
    }
    
    /**
     * Adds the specified cookie to the response.
     * 
     * @param Cookie $cookie
     */
    public function addCookie(Cookie $cookie)
    {
        $this->cookies[] = $cookie;
    }
    
    /**
     * Sends a temporary redirect response to the client using the specified redirect location URL.
     * 
     * @param string $location
     */
    public function sendRedirect($location) {
        $this->fireBeforeSending();
        $this->sendCookies();
        header('Location: '.$location);
        exit;
    }
    
    /**
     * Sends an error response to the client using the specified status.
     * 
     * @param int $code
     * @param string $message
     */
    public function sendError($code, $message = null) {
        $this->fireBeforeSending();
        if(empty($message)){
            $message = $this->getMessageFromStatus($code);
        }elseif(App::isDevMode()){
            die($message);
        }
        header($this->renderStatusLine($code, $message));
        exit;
    }
    
    public function send() {
        $this->fireBeforeSending();
        $this->sendHeaders();
        $this->sendBody();
        exit;
    }
    
    protected function sendHeaders()
    {
        $this->sendCookies();
        header($this->renderStatusLine($this->status, $this->getStatusMessage()));
        if(!empty($this->contentType)){
            $contentTypeStr = 'Content-Length : '.$this->contentLength;
            if(!empty($this->characterEncoding)){
                $contentTypeStr .= ' ' . $this->characterEncoding;
            }
            header($contentTypeStr);
        }
        if($this->contentLength !== null){
            header('Content-Length : '.$this->contentLength);
        }
        
        foreach ($this->headers as $name => $value) {
            header($name.': '.$value);
        }
    }
    
    protected function renderStatusLine($status, $message)
    {
        return trim(sprintf('%s %d %s', $this->protocol, $status, $message));
    }
    
    protected function sendCookies()
    {
        foreach ($this->cookies as $cookie){
            header('Set-Cookie: '.$cookie->getName().strstr($cookie->__toString(), '='), false, $this->status);
        }
    }
    protected function sendBody()
    {
        echo $this->body;
    }
    
    /**
     * @param int $status
     * @return string
     */
    public function getMessageFromStatus($status)
    {
        return isset(self::$statusMessages[$status]) ? self::$statusMessages[$status] : null;
    }
    
    /**
     * @return string
     */
    public function getStatusMessage()
    {
        return $this->getMessageFromStatus($this->status);
    }
    
    /**
     * Does the status code indicate a client error?
     *
     * @return bool
     */
    public function isClientError()
    {
        $code = $this->getStatus();
        return ($code < 500 && $code >= 400);
    }
    
    /**
     * Is the request forbidden due to ACLs?
     *
     * @return bool
     */
    public function isForbidden()
    {
        return (403 === $this->getStatus());
    }
    
    /**
     * Is the current status "informational"?
     *
     * @return bool
     */
    public function isInformational()
    {
        $code = $this->getStatus();
        return ($code >= 100 && $code < 200);
    }
    
    /**
     * Does the status code indicate the resource is not found?
     *
     * @return bool
     */
    public function isNotFound()
    {
        return (404 === $this->getStatus());
    }
    
    /**
     * Does the status code indicate the resource is gone?
     *
     * @return bool
     */
    public function isGone()
    {
        return (410 === $this->getStatus());
    }
    
    /**
     * Do we have a normal, OK response?
     *
     * @return bool
     */
    public function isOk()
    {
        return (200 === $this->getStatus());
    }
    
    /**
     * Does the status code reflect a server error?
     *
     * @return bool
     */
    public function isServerError()
    {
        $code = $this->getStatus();
        return (500 <= $code && 600 > $code);
    }
    
    /**
     * Do we have a redirect?
     *
     * @return bool
     */
    public function isRedirect()
    {
        $code = $this->getStatus();
        return (300 <= $code && 400 > $code);
    }
    
    /**
     * Was the response successful?
     *
     * @return bool
     */
    public function isSuccess()
    {
        $code = $this->getStatus();
        return (200 <= $code && 300 > $code);
    }
    
    /**
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns the name of the character encoding (MIME charset) used for the body sent in this response.
     * 
     * @return string
     */
    public function getCharacterEncoding()
    {
        return $this->characterEncoding;
    }

    /**
     * Returns the content type used for the MIME body sent in this response.
     * 
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @return int
     */
    public function getContentLength()
    {
        return $this->contentLength;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Returns the locale specified for this response
     * 
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Sets the status code for this response.
     * 
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Sets the character encoding (MIME charset) of the response being sent to the client, for example, to UTF-8.
     * 
     * @param string $characterEncoding
     */
    public function setCharacterEncoding($characterEncoding)
    {
        $this->characterEncoding = $characterEncoding;
    }

    /**
     * Sets the content type of the response being sent to the client.
     * 
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * Sets the length of the content body in the response In HTTP servlets, this method sets the HTTP Content-Length header.
     * 
     * @param int $contentLength
     */
    public function setContentLength($contentLength)
    {
        $this->contentLength = $contentLength;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * Sets the locale of the response, if the response has not been committed yet.
     * 
     * @param string $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }
    
    /**
     * @param \muuska\http\event\ResponseSendingListener $listener
     */
    public function addSendingListener(\muuska\http\event\ResponseSendingListener $listener)
    {
        $this->sendingListeners[] = $listener;
    }
    
    protected function fireBeforeSending()
    {
        $event = App::https()->createResponseSendingEvent($this);
        foreach ($this->sendingListeners as $listener) {
            $listener->beforeSend($event);
            if($event->isPropagationStopped()){
                break;
            }
        }
    }
}
