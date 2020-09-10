<?php
namespace muuska\translation\config;

use muuska\translation\constants\TranslationType;

class HelperTranslationConfig implements TranslatorConfig
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @param string $name
     */
    public function __construct($name){
        $this->setName($name);
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\config\TranslatorConfig::getType()
     */
    public function getType()
    {
        return TranslationType::HELPER;
    }
}