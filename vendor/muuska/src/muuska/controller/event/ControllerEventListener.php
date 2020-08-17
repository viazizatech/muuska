<?php
namespace muuska\controller\event;

interface ControllerEventListener
{
    /**
     * @param string $code
     * @param \muuska\controller\event\ControllerActionProcessingEvent $event
     */
    public function onControllerActionProcessing($code, \muuska\controller\event\ControllerActionProcessingEvent $event);
    
    /**
     * @param string $code
     * @param \muuska\controller\event\ControllerEvent $event
     */
    public function onControllerOtherEvent($code, \muuska\controller\event\ControllerEvent $event);
    
    /**
     * @param \muuska\controller\event\ControllerPageFormatingEvent $event
     */
    public function onControllerPageFormating(\muuska\controller\event\ControllerPageFormatingEvent $event);
}
