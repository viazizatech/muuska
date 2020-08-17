<?php
namespace muuska\translation;

interface ControllerTranslator extends Translator
{
    /**
     * @param string $relativeFile
     * @param bool $relatedToTheme
     * @return TemplateTranslator
     */
    public function getTemplateTranslator($relativeFile, $relatedToTheme = false);
    
    /**
     * @param string $relativeFile
     * @param bool $relatedToTheme
     * @return \muuska\translation\loader\TranslationLoader
     */
    public function getJsTranslationLoader($relativeFile, $relatedToTheme = false);
    
    /**
     * @param string $type
     * @param string $name
     * @return Translator
     */
    public function getOtherTranslator($type, $name);
}