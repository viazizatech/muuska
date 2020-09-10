<?php
namespace muuska\translation\loader;

use muuska\util\App;

class DefaultControllerTranslationLoader implements ControllerTranslationLoader
{
    /**
     * @var MultipleLoader
     */
    protected $multipleLoader;
    
    /**
     * @param MultipleLoader $multipleLoader
     */
    public function __construct(MultipleLoader $multipleLoader){
        $this->multipleLoader = $multipleLoader;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\loader\ControllerTranslationLoader::getMainLoader()
     */
    public function getMainLoader(){
        return $this->multipleLoader->getLoader('main');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\loader\ControllerTranslationLoader::getTemplateLoader()
     */
    public function getTemplateLoader(){
        return $this->getOtherLoader('templates');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\loader\ControllerTranslationLoader::getJsLoader()
     */
    public function getJsLoader(){
        return $this->getOtherLoader('js');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\loader\ControllerTranslationLoader::getOtherLoader()
     */
    public function getOtherLoader($type){
        return App::translations()->createDefaultMultipleLoader($this->multipleLoader->getLoader($type));
    }
}