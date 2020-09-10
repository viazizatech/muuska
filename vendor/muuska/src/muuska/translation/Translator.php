<?php
namespace muuska\translation;

interface Translator
{
    /**
     * @param string $lang
     * @param string $string
     * @param string $context
     */
    public function translate($lang, $string, $context = null);
}