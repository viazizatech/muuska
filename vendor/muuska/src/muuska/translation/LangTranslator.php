<?php
namespace muuska\translation;

interface LangTranslator extends Translator
{
    /**
     * @param string $string
     * @param string $context
     */
    public function l($string, $context = null);
    
    /**
     * @param string $lang
     * @return LangTranslator
     */
    public function getNew($lang);
}