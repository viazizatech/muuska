<?php
namespace muuska\dao\event;

class MultipleRowsDeleteEvent extends DAOEvent
{
    /**
     * @var \muuska\dao\util\DeleteConfig
     */
    protected $deleteConfig;
    
    /**
     * @param \muuska\dao\DAO $source
     * @param \muuska\dao\util\DeleteConfig $deleteConfig
     * @param array $params
     */
    public function __construct(\muuska\dao\DAO $source, \muuska\dao\util\DeleteConfig $deleteConfig = null, $params = array()){
        parent::__construct($source, $params);
        $this->deleteConfig = $deleteConfig;
    }
    
    /**
     * @return \muuska\dao\event\MultipleRowsDeleteEvent
     */
    public function createAfterEvent() {
        $this->defaultPrevented = false;
        $this->propagationStopped = false;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getFinalEventCode($code)
    {
        return self::EVENT_CODE_PREFIX.$code.'_multiple_rows_deleted';
    }
    
    /**
     * @return bool
     */
    public function hasDeleteConfig(){
        return ($this->deleteConfig !== null);
    }
    /**
     * @return \muuska\dao\util\DeleteConfig
     */
    public function getDeleteConfig()
    {
        return $this->deleteConfig;
    }
}
