<?php
namespace muuska\dao\event;

class ModelUpdateEvent extends DAOEvent
{
    /**
     * @var object
     */
    protected $model;
    
    /**
     * @var \muuska\dao\util\SaveConfig
     */
    protected $saveConfig;
    
    /**
     * @param \muuska\dao\DAO $source
     * @param object $model
     * @param \muuska\dao\util\SaveConfig $saveConfig
     * @param array $params
     */
    public function __construct(\muuska\dao\DAO $source, object $model, \muuska\dao\util\SaveConfig $saveConfig = null, $params = array()){
        parent::__construct($source, $params);
        $this->model = $model;
        $this->saveConfig = $saveConfig;
    }
    
    /**
     * @return \muuska\dao\event\ModelUpdateEvent
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
        return self::EVENT_CODE_PREFIX.$code.'_model_updated';
    }
    
    /**
     * @return bool
     */
    public function hasSaveConfig(){
        return ($this->saveConfig !== null);
    }
    
    /**
     * @return object
     */
    public function getModel()
    {
        return $this->model;
    }
    
    /**
     * @return \muuska\dao\util\SaveConfig
     */
    public function getSaveConfig()
    {
        return $this->saveConfig;
    }
}
