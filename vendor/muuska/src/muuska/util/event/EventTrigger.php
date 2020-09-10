<?php
namespace muuska\util\event;

interface EventTrigger
{
    /**
     * @param string $code
     * @param \muuska\dao\event\DAOEvent $event
     * @return bool
     */
    public function fireDAOEvent($code, \muuska\dao\event\DAOEvent $event);
    
    /**
     * @param string $code
     * @param \muuska\controller\event\ControllerActionProcessingEvent $event
     * @return bool
     */
    public function fireControllerActionProcessing($code, \muuska\controller\event\ControllerActionProcessingEvent $event);
    
    /**
     * @param string $code
     * @param \muuska\controller\event\ControllerEvent $event
     * @return bool
     */
    public function fireControllerOtherEvent($code, \muuska\controller\event\ControllerEvent $event);
    
    /**
     * @param \muuska\controller\event\ControllerPageFormatingEvent $event
     * @return bool
     */
    public function fireControllerPageFormating(\muuska\controller\event\ControllerPageFormatingEvent $event);
    
    /**
     * @param string $code
     * @param \muuska\http\event\RequestParsingEvent $event
     * @return bool
     */
    public function fireRequestParsing($code, \muuska\http\event\RequestParsingEvent $event);
    
    /**
     * @param string $code
     * @param \muuska\http\event\RequestParsingEvent $event
     * @return bool
     */
    public function fireRequestFinalParsing($code, \muuska\http\event\RequestParsingEvent $event);
    
    /**
     * @param \muuska\http\event\RouteLoadingEvent $event
     * @return bool
     */
    public function fireRouteLoading(\muuska\http\event\RouteLoadingEvent $event);
    
    /**
     * \muuska\url\UrlCreationEvent $event
     * @return bool
     */
    public function fireUrlCreation(\muuska\url\event\UrlCreationEvent $event);
    
    /**
     * @param \muuska\util\event\AppInitializationEvent $event
     * @return bool
     */
    public function fireAppInitialization(\muuska\util\event\AppInitializationEvent $event);
    
    /**
     * @param \muuska\mail\event\MailPageFormatingEvent $event
     * @return bool
     */
    public function fireMailPageFormating(\muuska\mail\event\MailPageFormatingEvent $event);
    
    /**
     * @param string $code
     * @param \muuska\util\event\EventObject $event
     * @return bool
     */
    public function fireOtherEvent($code, \muuska\util\event\EventObject $event);
}
