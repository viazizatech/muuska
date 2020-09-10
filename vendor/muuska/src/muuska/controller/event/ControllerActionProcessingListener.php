<?php
namespace muuska\controller\event;

interface ControllerActionProcessingListener
{
    /**
     * @param string $code
     * @param \muuska\controller\event\ControllerActionProcessingEvent $event
     */
    public function onAppControllerActionProcessing($code, \muuska\controller\event\ControllerActionProcessingEvent $event);
}
