<?php
namespace muuska\dao\util;

use muuska\util\App;

class DeleteConfig extends DataConfig
{
    /**
     * @var bool
     */
    protected $virtual = true;
    
    /**
     * @param boolean $virtual
     */
    public function __construct($virtual = true) {
        $this->setVirtual($virtual);
    }
    
    public function createSaveConfig()
    {
        $saveConfig = App::daos()->createSaveConfig($this->lang);
        $saveConfig->setRestrictionFields($this->restrictionFields);
        $saveConfig->setLogicalOperator($this->logicalOperator);
        return $saveConfig;
    }
    
    /**
     * @return boolean
     */
    public function isVirtual()
    {
        return $this->virtual;
    }

    /**
     * @param boolean $virtual
     */
    public function setVirtual($virtual)
    {
        $this->virtual = $virtual;
    }
}
