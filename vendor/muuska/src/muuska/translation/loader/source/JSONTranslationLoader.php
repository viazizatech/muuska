<?php
namespace muuska\translation\loader\source;

use muuska\translation\loader\AbstractTranslationLoader;
use muuska\util\App;

class JSONTranslationLoader extends AbstractTranslationLoader
{
    /**
     * @var string
     */
    protected $filePattern;
    
    /**
     * @param string $filePattern
     */
    public function __construct($filePattern) {
        $this->filePattern = $filePattern;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\loader\AbstractTranslationLoader::loadTranslations()
     */
    protected function loadTranslations($lang)
    {
        $fullFile = str_replace('{lang}', $lang, $this->filePattern).'.json';
        return App::getFileTools()->getArrayFromJsonFile($fullFile);
    }
}