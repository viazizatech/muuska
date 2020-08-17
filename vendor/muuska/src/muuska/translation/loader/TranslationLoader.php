<?php
namespace muuska\translation\loader;

interface TranslationLoader
{
    /**
     * @param string $lang
     * @return array
     */
    public function getTranslations($lang);
}