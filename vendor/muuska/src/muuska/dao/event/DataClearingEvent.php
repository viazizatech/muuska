<?php
namespace muuska\dao\event;

class DataClearingEvent extends DAOEvent
{
    /**
     * @return string
     */
    public function getFinalEventCode($code)
    {
        return self::EVENT_CODE_PREFIX.$code.'_data_clearing';
    }
    
    /**
     * @return \muuska\dao\event\DataClearingEvent
     */
    public function createAfterEvent() {
        $this->defaultPrevented = false;
        $this->propagationStopped = false;
        return $this;
    }
}
