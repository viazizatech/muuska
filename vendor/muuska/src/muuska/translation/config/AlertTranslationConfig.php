<?php
namespace muuska\translation\config;

use muuska\translation\constants\TranslationType;

class AlertTranslationConfig implements TranslatorConfig
{
    /**
     * @var string
     */
    protected $alertType;
    
    /**
     * @param string $alertType
     */
    public function __construct($alertType){
        $this->setAlertType($alertType);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\config\TranslatorConfig::getName()
     */
    public function getName()
    {
        return $this->alertType;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\config\TranslatorConfig::getType()
     */
    public function getType()
    {
        return TranslationType::ALERT;
    }
    
    /**
     * @return string
     */
    public function getAlertType()
    {
        return $this->alertType;
    }

    /**
     * @param string $alertType
     */
    public function setAlertType($alertType)
    {
        $this->alertType = $alertType;
    }
}