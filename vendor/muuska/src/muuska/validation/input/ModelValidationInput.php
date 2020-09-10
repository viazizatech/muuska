<?php
namespace muuska\validation\input;

class ModelValidationInput implements ValidationInput{
    /**
     * @var \muuska\dao\DAO
     */
    protected $dao;
    
    /**
     * @var object
     */
    protected $model;
    
    /**
     * @var \muuska\dao\util\SaveConfig
     */
    protected $saveConfig;
    
    /**
     * @var bool
     */
    protected $update;
    
    /**
     * @var string
     */
    protected $lang;
    
    /**
     * @var ModelValidationInput
     */
    protected $parentInput;
    
    /**
     * @param object $model
     * @param string $lang
     * @param \muuska\dao\DAO $dao
     * @param \muuska\dao\util\SaveConfig $saveConfig
     * @param boolean $update
     * @param \muuska\validation\input\ModelValidationInput $parentInput
     */
    public function __construct(object $model, $lang, \muuska\dao\DAO $dao = null, \muuska\dao\util\SaveConfig $saveConfig = null, $update = false, \muuska\validation\input\ModelValidationInput $parentInput = null) {
        $this->model = $model;
        $this->lang = $lang;
        $this->dao = $dao;
        $this->saveConfig = $saveConfig;
        $this->update = $update;
        $this->parentInput = $parentInput;
    }
    
    /**
     * @return bool
     */
    public function hasSaveConfig() {
        return ($this->saveConfig !== null);
    }
    
    /**
     * @return bool
     */
    public function hasParentInput() {
        return ($this->parentInput !== null);
    }
    
    /**
     * @return bool
     */
    public function hasDao() {
        return ($this->dao !== null);
    }
    
    /**
     * @return \muuska\dao\DAO
     */
    public function getDao()
    {
        return $this->dao;
    }

    /**
     * @return \muuska\dao\util\SaveConfig
     */
    public function getSaveConfig()
    {
        return $this->saveConfig;
    }

    /**
     * @return boolean
     */
    public function isUpdate()
    {
        return $this->update;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }
    
    /**
     * @return object
     */
    public function getValue()
    {
        return $this->model;
    }
    
    /**
     * @return \muuska\validation\input\ModelValidationInput
     */
    public function getParentInput()
    {
        return $this->parentInput;
    }
}