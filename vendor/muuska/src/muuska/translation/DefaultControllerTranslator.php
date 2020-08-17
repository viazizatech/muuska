<?php
namespace muuska\translation;

use muuska\util\App;

class DefaultControllerTranslator extends DefaultTranslator implements ControllerTranslator
{
    /**
     * @var \muuska\translation\loader\ControllerTranslationLoader
     */
    protected $controllerTranslationLoader;
    
    /**
     * @param \muuska\translation\loader\ControllerTranslationLoader $controllerTranslationLoader
     * @param \muuska\translation\Translator $alternativeTranslator
     */
    public function __construct(\muuska\translation\loader\ControllerTranslationLoader $controllerTranslationLoader, \muuska\translation\Translator $alternativeTranslator = null){
        parent::__construct($controllerTranslationLoader->getMainLoader(), $alternativeTranslator);
        $this->controllerTranslationLoader = $controllerTranslationLoader;
    }
    
    /**
     * @param string $relativeFile
     * @return \muuska\translation\DefaultTemplateTranslator
     */
    public function getNewTranslator($relativeFile)
    {
        $result = $this;
        if($this->multipleLoader !== null){
            $result = App::translations()->createDefaultTemplateTranslator($this->multipleLoader->getLoader($relativeFile), $this->multipleLoader, $this->keyPrefix, $this->alternativeTranslator);
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\ControllerTranslator::getJsTranslationLoader()
     */
    public function getJsTranslationLoader($relativeFile, $relatedToTheme = false)
    {
        $result = null;
        $jsLoader = $this->controllerTranslationLoader->getJsLoader();
        if($jsLoader !== null){
            $result = $jsLoader->getLoader(App::getTools()->getTranslationRelativeFileKey($relativeFile, $relatedToTheme));
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\ControllerTranslator::getTemplateTranslator()
     */
    public function getTemplateTranslator($relativeFile, $relatedToTheme = false)
    {
        $result = null;
        $templateLoader = $this->controllerTranslationLoader->getTemplateLoader();
        if($templateLoader !== null){
            $result = App::translations()->createDefaultTemplateTranslator($templateLoader->getLoader(App::getTools()->getTranslationRelativeFileKey($relativeFile, $relatedToTheme)), $templateLoader, App::getTools()->getThemeTranslationPrefix($relatedToTheme), $this->alternativeTranslator);
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\translation\ControllerTranslator::getOtherTranslator()
     */
    public function getOtherTranslator($type, $name)
    {
        $loader = $this->getOtherLoader($type, $name);
        return ($loader !== null) ? App::translations()->createDefaultTranslator($loader, $this->alternativeTranslator) : $this;
    }
    
    /**
     * @param string $type
     * @param string $name
     * @return \muuska\translation\loader\TranslationLoader
     */
    public function getOtherLoader($type, $name)
    {
        $otherLoader = $this->controllerTranslationLoader->getOtherLoader($type);
        return ($otherLoader !== null) ? $otherLoader->getLoader($name) : null;
    }
}