<?php
namespace muuska\translation\config;

use muuska\translation\constants\TranslationType;

class ValidationTranslationConfig implements TranslatorConfig
{
    /**
     * {@inheritDoc}
     * @see \muuska\translation\config\TranslatorConfig::getName()
     */
    public function getName()
    {
        return '';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\config\TranslatorConfig::getType()
     */
    public function getType()
    {
        return TranslationType::VALIDATION;
    }
}