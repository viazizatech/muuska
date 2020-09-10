<?php
namespace muuska\translation\config;

use muuska\translation\constants\TranslationType;

class ModelTranslationConfig implements TranslatorConfig
{
    /**
     * @var \muuska\model\ModelDefinition
     */
    protected $modelDefinition;
    
    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     */
    public function __construct(\muuska\model\ModelDefinition $modelDefinition){
        $this->setModelDefinition($modelDefinition);
    }
    
    /**
     * @return \muuska\model\ModelDefinition
     */
    public function getModelDefinition()
    {
        return $this->modelDefinition;
    }

    /**
     * @param \muuska\model\ModelDefinition $modelDefinition
     */
    public function setModelDefinition($modelDefinition)
    {
        $this->modelDefinition = $modelDefinition;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\config\TranslatorConfig::getName()
     */
    public function getName()
    {
        return $this->modelDefinition->getName();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\config\TranslatorConfig::getType()
     */
    public function getType()
    {
        return TranslationType::MODEL;
    }
}