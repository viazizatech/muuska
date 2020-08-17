<?php
namespace muuska\controller\event;

class ControllerActionProcessingEvent extends ControllerEvent
{
    /**
     * {@inheritDoc}
     * @see \muuska\controller\event\ControllerEvent::getFinalEventCode()
     */
    public function getFinalEventCode($code)
    {
        return parent::getFinalEventCode('action_processing_'.$code);
    }
    
    /**
     * @return \muuska\controller\event\ControllerActionProcessingEvent
     */
    public function createAfterEvent() {
        $this->propagationStopped = false;
        $this->defaultPrevented = false;
        return $this;
    }
}
