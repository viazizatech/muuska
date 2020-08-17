<?php
namespace muuska\translation;

interface TemplateTranslator extends Translator
{
    /**
     * @param string $relativeFile
     * @return TemplateTranslator
     */
    public function getNewTranslator($relativeFile);
}