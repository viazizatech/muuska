<?php
namespace muuska\http\session;

use muuska\http\VisitorInfoRecorder;

interface Session extends VisitorInfoRecorder
{
    /**
     * Returns an Enumeration of String objects containing the names of all the objects bound to this session.
     * 
     * @return int
     */
    public function getCreationTime();
    
    /**
     * Returns the time when this session was created, measured in milliseconds since midnight January 1, 1970 GMT.
     * 
     * @return string
     */
    public function getId();
    
    /**
     * Returns the last time the client sent a request associated with this session, as the number of milliseconds since midnight January 1, 1970 GMT, and marked by the time the container received the request.
     * 
     * @return int
     */
    public function getLastAccessedTime();
    
    /**
     * Returns the maximum time interval, in seconds, that the servlet container will keep this session open between client accesses.
     * 
     * @return int
     */
    public function getMaxInactiveInterval();
    
    /**
     * Invalidates this session then unbinds any objects bound to it.
     */
    public function invalidate();
    
    /**
     * Returns true if the client does not yet know about the session or if the client chooses not to join the session.
     * 
     * @return bool
     */
    public function isNew();
    
    /**
     * Specifies the time, in seconds, between client requests before the servlet container will invalidate this session.
     * 
     * @param int $maxInactiveInterval
     */
    public function setMaxInactiveInterval($maxInactiveInterval);
    
    /**
     * @return bool
     */
    public function destroy();
}
