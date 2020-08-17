<?php
namespace muuska\translation\config;

use muuska\translation\constants\TranslationType;

class ControllerTranslationConfig implements TranslatorConfig
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var bool
     */
    protected $relatedToTheme;
    
    /**
     * @param string $name
     * @param boolean $relatedToTheme
     */
    public function __construct($name, $relatedToTheme = true){
        $this->setName($name);
        $this->setRelatedToTheme($relatedToTheme);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\config\TranslatorConfig::getName()
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\config\TranslatorConfig::getType()
     */
    public function getType()
    {
        return TranslationType::CONTROLLER;
    }

    /**
     * @return boolean
     */
    public function isRelatedToTheme()
    {
        return $this->relatedToTheme;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    /**
     * @param boolean $relatedToTheme
     */
    public function setRelatedToTheme($relatedToTheme)
    {
        $this->relatedToTheme = $relatedToTheme;
    }
}