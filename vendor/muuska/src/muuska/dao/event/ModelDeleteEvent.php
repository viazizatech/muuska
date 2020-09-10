<?php
namespace muuska\dao\event;

class ModelDeleteEvent extends DAOEvent
{
    /**
     * @var object
     */
    protected $model;
    
    /**
     * @var \muuska\dao\util\DeleteConfig
     */
    protected $deleteConfig;
    
    /**
     * @param \muuska\dao\DAO $source
     * @param object $model
     * @param \muuska\dao\util\DeleteConfig $deleteConfig
     * @param array $params
     */
    public function __construct(\muuska\dao\DAO $source, object $model, \muuska\dao\util\DeleteConfig $deleteConfig = null, $params = array()){
        parent::__construct($source, $params);
        $this->model = $model;
        $this->deleteConfig = $deleteConfig;
    }
    
    /**
     * @return \muuska\dao\event\ModelDeleteEvent
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
        return self::EVENT_CODE_PREFIX.$code.'_model_deleted';
    }
    
    /**
     * @return bool
     */
    public function hasDeleteConfig(){
        return ($this->deleteConfig !== null);
    }
    /**
     * @return object
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return \muuska\dao\util\DeleteConfig
     */
    public function getDeleteConfig()
    {
        return $this->deleteConfig;
    }
}
