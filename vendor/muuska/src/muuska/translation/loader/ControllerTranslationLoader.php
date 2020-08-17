<?php
namespace muuska\translation\loader;

interface ControllerTranslationLoader
{
    /**
     * @return TranslationLoader
     */
    public function getMainLoader();
    
    /**
     * @return MultipleLoader
     */
    public function getTemplateLoader();
    
    /**
     * @return MultipleLoader
     */
    public function getJsLoader();
    
    /**
     * @param string $type
     * @return MultipleLoader
     */
    public function getOtherLoader($type);
}