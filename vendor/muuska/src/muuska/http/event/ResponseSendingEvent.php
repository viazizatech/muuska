<?php
namespace muuska\http\event;

use muuska\util\event\EventObject;

class ResponseSendingEvent extends EventObject
{
    /**
     * @param \muuska\http\Response $source
     * @param array $params
     */
    public function __construct(\muuska\http\Response $source, $params = array()){
		parent::__construct($source, $params);
	}
	
	/**
	 * @param \muuska\http\Cookie $cookie
	 */
	public function addCookie(\muuska\http\Cookie $cookie) {
	    $this->source->addCookie($cookie);
	}
}
