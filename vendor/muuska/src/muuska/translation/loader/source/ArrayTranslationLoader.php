<?php
namespace muuska\translation\loader\source;

use muuska\translation\loader\TranslationLoader;

class ArrayTranslationLoader implements TranslationLoader
{
    /**
     * @var array
     */
    protected $array;
    
    /**
     * @param array $array
     */
    public function __construct($array) {
        $this->array = $array;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\loader\TranslationLoader::getTranslations()
     */
    public function getTranslations($lang) {
        return isset($this->array[$lang]) ? $this->array[$lang] : array();
    }
}