<?php
namespace muuska\dao\event;

class DataChangeEvent extends DAOEvent
{
    /**
     * @var string
     */
    protected $changeCode;
    
    /**
     * @var object
     */
    protected $model;
    
    /**
     * @var \muuska\dao\util\DataConfig
     */
    protected $dataConfig;
    
    /**
     * @param \muuska\dao\DAO $source
     * @param string $changeCode
     * @param \muuska\dao\util\DataConfig $dataConfig
     * @param object $model
     * @param array $params
     */
    public function __construct(\muuska\dao\DAO $source, $changeCode, \muuska\dao\util\DataConfig $dataConfig = null, object $model = null, $params = array()){
        parent::__construct($source, $params);
        $this->changeCode = $changeCode;
        $this->dataConfig = $dataConfig;
        $this->model = $model;
    }
    
    /**
     * @return string
     */
    public function getFinalEventCode($code)
    {
        return self::EVENT_CODE_PREFIX.$code.'_data_changed';
    }
    
    /**
     * @param string $changeCode
     * @return bool
     */
    public function checkChangeCode($changeCode){
        return ($this->changeCode === $changeCode);
    }
    
    /**
     * @return \muuska\dao\event\DataChangeEvent
     */
    public function createAfterEvent() {
        $this->defaultPrevented = false;
        $this->propagationStopped = false;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function hasModel(){
        return ($this->model !== null);
    }
    
    /**
     * @return bool
     */
    public function hasDataConfig(){
        return ($this->dataConfig !== null);
    }
    
    /**
     * @return string
     */
    public function getChangeCode()
    {
        return $this->changeCode;
    }

    /**
     * @return object
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return \muuska\dao\util\DataConfig
     */
    public function getDataConfig()
    {
        return $this->dataConfig;
    }
}
