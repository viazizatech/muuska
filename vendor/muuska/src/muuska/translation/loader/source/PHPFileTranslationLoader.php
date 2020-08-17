<?php
namespace muuska\translation\loader\source;

use muuska\translation\loader\AbstractTranslationLoader;

class PHPFileTranslationLoader extends AbstractTranslationLoader
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
        $result = array();
        $fullFile = str_replace('{lang}', $lang, $this->filePattern).'.php';
        if(file_exists($fullFile)){
            $data = $this->loadTranslationsFromPhpFile($fullFile);
            if(is_array($data)){
                $result = $data;
            }
        }
        return $result;
    }
    
    /**
     * @param string $fullFile
     */
    protected function loadTranslationsFromPhpFile($fullFile)
    {
        include $fullFile;
    }
}