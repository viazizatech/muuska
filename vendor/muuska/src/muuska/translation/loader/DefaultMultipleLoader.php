<?php
namespace muuska\translation\loader;

use muuska\util\App;

class DefaultMultipleLoader implements MultipleLoader
{
    /**
     * @var TranslationLoader
     */
    protected $mainLoader;
    
    /**
     * @param TranslationLoader $mainLoader
     */
    public function __construct(TranslationLoader $mainLoader) {
        $this->mainLoader = $mainLoader;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\loader\MultipleLoader::getLoader()
     */
    public function getLoader($key)
    {
        return App::translations()->createTreeTranslationLoader($this->mainLoader, $key);
    }
}