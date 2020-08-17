<?php
namespace muuska\dao\event;

use muuska\util\event\EventObject;

class DAOEvent extends EventObject
{
    const EVENT_CODE_PREFIX = 'dao_';
    
    /**
     * @param \muuska\dao\DAO $source
     * @param array $params
     */
    public function __construct(\muuska\dao\DAO $source, $params = array()){
        parent::__construct($source, $params);
    }
    
    /**
     * @param string $value
     * @return bool
     */
    public function checkName($value) {
        return ($this->source->getModelDefinition()->getName() === $value);
    }
    
    /**
     * @param string $value
     * @return bool
     */
    public function checkFullName($value) {
        return ($this->source->getModelDefinition()->getFullName() === $value);
    }
    
    /**
     * @return string
     */
    public function getFinalEventCode($code)
    {
        return self::EVENT_CODE_PREFIX.$code;
    }
    
    /**
     * @return \muuska\project\Project
     */
    public function getProject()
    {
        return $this->source->getProject();
    }
}
