<?php
namespace muuska\translation;

use muuska\translation\constants\TranslationType;
use muuska\util\App;
use muuska\translation\constants\TranslationLoaderSourceType;
use muuska\constants\FolderPath;

class TranslationStore
{
    /**
     * @var array
     */
    protected $translators = array();
    
    /**
     * @var array
     */
    protected $multipleLoaders;
    
    /**
     * @var string
     */
    protected $baseDir;
    
    /**
     * @var string
     */
    protected $loaderSource;
    
    /**
     * @var string
     */
    protected $themeName;
    
    /**
     * @var bool
     */
    protected $themePrefixEnabled;
    
    /**
     * @param string $baseDir
     * @param boolean $themePrefixEnabled
     * @param string $themeName
     * @param string $loaderSource
     */
    public function __construct($baseDir, $themePrefixEnabled = false, $themeName = null, $loaderSource = null){
        $this->baseDir = $baseDir;
        $this->themePrefixEnabled = $themePrefixEnabled;
        $this->themeName = $themeName;
        $this->loaderSource = $loaderSource;
    }
    
    /**
     * @param \muuska\translation\config\TranslatorConfig $translatorConfig
     * @param Translator $alternativeTranslator
     * @return Translator
     */
    public function getTranslator(\muuska\translation\config\TranslatorConfig $translatorConfig, Translator $alternativeTranslator = null) {
        $key = $translatorConfig->getType() . $translatorConfig->getName();
        if(!array_key_exists($key, $this->translators)){
            $this->translators[$key] = $this->createTranslator($translatorConfig, $alternativeTranslator);
        }
        return $this->translators[$key];
    }
    
    /**
     * @param \muuska\translation\config\TranslatorConfig $translatorConfig
     * @param Translator $alternativeTranslator
     * @return Translator
     */
    protected function createTranslator(\muuska\translation\config\TranslatorConfig $translatorConfig, Translator $alternativeTranslator = null) {
        $type = $translatorConfig->getType();
        $name = $translatorConfig->getName();
        $result = null;
        if ($type === TranslationType::TEMPLATE){
            $relatedToTheme = $translatorConfig->isRelatedToTheme();
            $multipleLoader = $this->getMultipleLoader(TranslationType::TEMPLATE, $this->getFileFromTheme('all', 'templates/', $relatedToTheme));
            $result = App::translations()->createDefaultTemplateTranslator($multipleLoader->getLoader($this->getFileKeyFromTheme($translatorConfig->getName(), $relatedToTheme)), $multipleLoader, $this->getThemeTranslationPrefix($relatedToTheme), $alternativeTranslator);
        }elseif($type === TranslationType::CONTROLLER){
            $file = $this->getFileFromTheme($name, 'controllers/', $translatorConfig->isRelatedToTheme());
            $controllerLoader = App::translations()->createDefaultControllerTranslationLoader(App::translations()->createDefaultMultipleLoader($this->createLoader($file)));
            $result = App::translations()->createDefaultControllerTranslator($controllerLoader, $alternativeTranslator);
        }elseif ($type === TranslationType::MODEL){
            $result = $this->createTranslatorFromFile('models/'.$name, $alternativeTranslator);
        }/*elseif ($type === TranslationType::ALERT){
            $result = $this->createTranslatorFromFile('alerts/'.$name, $alternativeTranslator);
        }*/elseif(in_array($type, array(TranslationType::MAIN, TranslationType::VALIDATION))){
            $result = $this->createTranslatorFromFile(strtolower($type), $alternativeTranslator);
        }elseif($type === TranslationType::JS){
            $relatedToTheme = $translatorConfig->isRelatedToTheme();
            $multipleLoader = $this->getMultipleLoader(TranslationType::JS, $this->getFileFromTheme('all', 'js/', $relatedToTheme));
            $result = App::translations()->createDefaultTranslator($multipleLoader->getLoader($this->getFileKeyFromTheme($translatorConfig->getName(), $relatedToTheme)), $alternativeTranslator);
        }elseif(in_array($type, array(TranslationType::HELPER, TranslationType::CUSTOM, TranslationType::OPTION, TranslationType::ALERT))){
            $result = $this->createTranslatorFromMultiple($name, $type, strtolower($type).'s', $alternativeTranslator);
        }else{
            $result = $this->createTranslatorFromFile(strtolower($type).'/'.$name, $alternativeTranslator);
        }
        return $result;
    }
    /**
     * @param string $relativeFile
     * @param string $basePath
     * @param bool $relatedToTheme
     * @return string
     */
    public function getFileFromTheme($relativeFile, $basePath, $relatedToTheme) {
        return $basePath . $this->getThemePath($basePath, $relatedToTheme).$relativeFile;
    }
    
    /**
     * @param string $basePath
     * @param bool $relatedToTheme
     * @return string
     */
    public function getThemePath($basePath, $relatedToTheme) {
        return (($relatedToTheme && $this->themePrefixEnabled && !empty($this->themeName)) ? FolderPath::ALL_THEMES.'/'.$this->themeName .'/'  : '');
    }
    
    /**
     * @param string $relativeFile
     * @param bool $relatedToTheme
     * @return string
     */
    public function getFileKeyFromTheme($relativeFile, $relatedToTheme) {
        return $this->themePrefixEnabled ? App::getTools()->getTranslationRelativeFileKey($relativeFile, $relatedToTheme) : '';
    }
    
    /**
     * @param bool $relatedToTheme
     * @return string
     */
    public function getThemeTranslationPrefix($relatedToTheme) {
        return $this->themePrefixEnabled ? App::getTools()->getThemeTranslationPrefix($relatedToTheme) : '';
    }
    
    /**
     * @param string $key
     * @param string $file
     * @return \muuska\translation\loader\MultipleLoader
     */
    public function getMultipleLoader($key, $file = null) {
        if(empty($file)){
            $file = strtolower($key);
        }
        if(!isset($this->multipleLoaders[$key])){
            $this->multipleLoaders[$key] = App::translations()->createDefaultMultipleLoader($this->createLoader($file));
        }
        return $this->multipleLoaders[$key];
    }
    
    /**
     * @param string $lang
     * @param string $location
     * @param bool $relatedToTheme
     * @param bool $scope
     * @param bool $defaultScopeEnabled
     * @param int $priority
     * @param int $locationInPage
     * @return \muuska\asset\AssetTranslation
     */
    public function createJSTranslation($lang, $location, $relatedToTheme = true, $scope = null, $defaultScopeEnabled = true, $priority = null, $locationInPage = null){
        $key = $this->getFileKeyFromTheme($location, $relatedToTheme);
        return App::assets()->createAssetTranslation($this->getMultipleLoader(TranslationType::JS)->getLoader($key), $key, $lang, $scope, $defaultScopeEnabled, $priority, $locationInPage);
    }
    
    /**
     * @param string $filePattern
     * @return \muuska\translation\loader\TranslationLoader
     */
    protected function createLoader($filePattern) {
        $result = null;
        $filePattern = $this->baseDir.$filePattern;
        if(empty($this->loaderSource) || ($this->loaderSource === TranslationLoaderSourceType::JSON)){
            $result = App::translations()->createJSONTranslationLoader($filePattern);
        }elseif($this->loaderSource === TranslationLoaderSourceType::PHP){
            $result = App::translations()->createPHPFileTranslationLoader($filePattern);
        }else {
            $result = App::translations()->createArrayTranslationLoader(array());
        }
        return $result;
    }
    
    /**
     * @param string $filePattern
     * @param Translator $alternativeTranslator
     * @return \muuska\translation\Translator
     */
    protected function createTranslatorFromFile($filePattern, Translator $alternativeTranslator = null) {
        return App::translations()->createDefaultTranslator($this->createLoader($filePattern), $alternativeTranslator);
    }
    
    /**
     * @param string $name
     * @param string $multipleKey
     * @param string $multipleFile
     * @return \muuska\translation\loader\TranslationLoader
     */
    protected function getLoaderFromMultiple($name, $multipleKey, $multipleFile = null) {
        return $this->getMultipleLoader($multipleKey, $multipleFile)->getLoader($name);
    }
    
    /**
     * @param string $name
     * @param string $multipleKey
     * @param string $multipleFile
     * @param Translator $alternativeTranslator
     * @return \muuska\translation\Translator
     */
    protected function createTranslatorFromMultiple($name, $multipleKey, $multipleFile = null, Translator $alternativeTranslator = null) {
        return App::translations()->createDefaultTranslator($this->getLoaderFromMultiple($name, $multipleKey, $multipleFile), $alternativeTranslator);
    }
}