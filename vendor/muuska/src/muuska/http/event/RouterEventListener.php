<?php
namespace muuska\url\event;

interface RouterEventListener
{
    /**
     * @param string $code
     * @param \muuska\http\event\RequestParsingEvent $event
     */
    public function onRequestParsing($code, \muuska\http\event\RequestParsingEvent $event);
    
    /**
     * @param string $code
     * @param \muuska\http\event\RequestParsingEvent $event
     */
    public function onRequestFinalParsing($code, \muuska\http\event\RequestParsingEvent $event);
    
    /**
     * @param \muuska\http\event\RouteLoadingEvent $event
     */
    public function onRouteLoading(\muuska\http\event\RouteLoadingEvent $event);
}