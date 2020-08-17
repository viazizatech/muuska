<?php
namespace muuska\controller\event;

interface ControllerOtherEventListener
{
    /**
     * @param string $code
     * @param \muuska\controller\event\ControllerEvent $event
     */
    public function onAppControllerOtherEvent($code, \muuska\controller\event\ControllerEvent $event);
}
