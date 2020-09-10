<?php
namespace muuska\translation\loader;

class TreeTranslationLoader extends AbstractTranslationLoader
{
    /**
     * @var TranslationLoader
     */
    protected $mainLoader;
    
    /**
     * @var string
     */
    protected $key;
    
    /**
     * @param TranslationLoader $mainLoader
     * @param string $key
     */
    public function __construct(TranslationLoader $mainLoader, $key) {
        $this->mainLoader = $mainLoader;
        $this->key = $key;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\loader\AbstractTranslationLoader::loadTranslations()
     */
    protected function loadTranslations($lang)
    {
        $mainLoaderTranslations = $this->mainLoader->getTranslations($lang);
        return isset($mainLoaderTranslations[$this->key]) ? $mainLoaderTranslations[$this->key] : array();
    }
}