<?php
namespace muuska\util\event;

interface AppEventListener
{
    /**
     * @param string $code
     * @param \muuska\util\event\EventObject $event
     */
    public function onOtherEvent($code, \muuska\util\event\EventObject $event);
}
