<?php
namespace muuska\controller\event;

interface ControllerPageFormatingListener
{
    /**
     * @param \muuska\controller\event\ControllerPageFormatingEvent $event
     */
    public function onAppControllerPageFormating(\muuska\controller\event\ControllerPageFormatingEvent $event);
}
