<?php
namespace muuska\translation\loader;

abstract class AbstractTranslationLoader implements TranslationLoader
{
    /**
     * @var array
     */
    protected $translations;
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\loader\TranslationLoader::getTranslations()
     */
    public function getTranslations($lang) {
        if(!isset($this->translations[$lang])){
            $data = $this->loadTranslations($lang);
            $this->translations[$lang] = is_array($data) ? $data : array();
        }
        return $this->translations[$lang];
    }
    
    /**
     * @param string $lang
     * @return array
     */
    protected abstract function loadTranslations($lang);
}