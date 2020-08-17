<?php
namespace muuska\translation\loader;

interface MultipleLoader
{
    /**
     * @param string $key
     * @return TranslationLoader
     */
    public function getLoader($key);
}