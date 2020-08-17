<?php
namespace muuska\util\theme;

use muuska\asset\constants\AssetNames;
use muuska\asset\constants\AssetPriority;
use muuska\asset\constants\AssetType;
use muuska\constants\FolderPath;
use muuska\html\constants\ButtonStyle;
use muuska\util\App;

class AbstractTheme implements Theme
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $corePath;
    
    /**
     * @var \muuska\config\Configuration
     */
    protected $mainConfig;
    
    private $tmpData;
    
    /**
     * @param string $name
     * @param string $corePath
     * @param \muuska\config\Configuration $mainConfig
     */
    public function __construct($name, $corePath, \muuska\config\Configuration $mainConfig = null){
        $this->name = $name;
        $this->corePath = $corePath;
        $this->mainConfig = $mainConfig;
        $this->init();
    }
    
    /**
     * @param string $name
     * @param string $templatePath
     */
    protected function addAvailableComponent($name, $templatePath){
        $this->tmpData['availableComponents'][$name] = $templatePath;
    }
    
    /**
     * @param string $originalClass
     * @param string $finalClass
     */
    protected function setWidthClass($originalClass, $finalClass){
        $this->tmpData['widthClasses'][$originalClass] = $finalClass;
    }
    
    /**
     * @param string $style
     * @param string $class
     */
    protected function setCommandStyle($style, $class){
        $this->tmpData['commandStyles'][$style] = $class;
    }
    
    /**
     * @param string $style
     * @param string $class
     */
    protected function setCommandSecondStyle($style, $class){
        $this->tmpData['commandSecondStyles'][$style] = $class;
    }
    
    /**
     * @param string $originalClass
     * @param string $finalClass
     */
    protected function setCommandStyleClass($originalClass, $finalClass){
        $this->tmpData['commandStyleClasses'][$originalClass] = $finalClass;
    }
    
    /**
     * @param string $originalClass
     * @param string $finalClass
     */
    protected function setAlertStyleClass($originalClass, $finalClass){
        $this->tmpData['alertStyleClasses'][$originalClass] = $finalClass;
    }
    
    /**
     * @param string $name
     * @return bool
     */
    public function isComponentAvailable($name){
        return (isset($this->tmpData['availableComponents']) && isset($this->tmpData['availableComponents'][$name]));
    }
    
    /**
     * @param string $name
     * @return string
     */
    public function getComponentTemplatePath($name){
        return $this->isComponentAvailable($name) ? $this->tmpData['availableComponents'][$name] : null;
    }
    
    protected function init(){}
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::createAssetGroup()
     */
    public function createAssetGroup($name, \muuska\asset\AssetSetter $assetSetter){
        $group = null;
        if($name === AssetNames::THEME_DEFAULT_GROUP){
            $group = $this->createDefaultAssetGroup($name, $assetSetter);
        }elseif($name === AssetNames::THEME_MAIL_DEFAULT_GROUP){
            $group = $this->createMailDefaultAssetGroup($name, $assetSetter);
        }elseif(App::getStringTools()->endsWith($name, AssetNames::THEME_DEFAULT_GROUP)){
            $group = $this->createSubAppAssetGroup($name, str_replace('_'.AssetNames::THEME_DEFAULT_GROUP, '', $name), $assetSetter);
        }
        if(($group !== null) && !$assetSetter->hasAssetGroup($name)){
            $assetSetter->addAssetGroup($group);
        }
        return $group;
    }
    
    /**
     * @param string $name
     * @param \muuska\asset\AssetSetter $assetSetter
     * @return \muuska\asset\AssetGroup
     */
    public function createDefaultAssetGroup($name, \muuska\asset\AssetSetter $assetSetter){
        return null;
    }
    
    /**
     * @param string $name
     * @param \muuska\asset\AssetSetter $assetSetter
     * @return \muuska\asset\AssetGroup
     */
    public function createMailDefaultAssetGroup($name, \muuska\asset\AssetSetter $assetSetter){
        return null;
    }
    
    /**
     * @param string $name
     * @param string $subAppName
     * @param \muuska\asset\AssetSetter $assetSetter
     * @return \muuska\asset\AssetGroup
     */
    public function createSubAppAssetGroup($name, $subAppName, \muuska\asset\AssetSetter $assetSetter){
        return null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::getAssetResolver()
     */
    public function getAssetResolver(){
        if(!isset($this->tmpData['assetResolver'])){
            $subPattern = $this->getAssetPathPattern();
            $this->tmpData['assetResolver'] = App::assets()->createDefaultRelativeAssetResolver(App::getApp()->getPublicUrl().$subPattern, App::getApp()->getPublicDir().$subPattern);
        }
        return $this->tmpData['assetResolver'];
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::createAsset()
     */
    public function createAsset($assetType, $location, $library = null, $priority = null, $locationInPage = null){
        return App::assets()->createRelativeUriAsset($assetType, $location, $this->getAssetResolver(), $library, $priority, $locationInPage);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::createHtmlImage()
     */
    public function createHtmlImage($location, $alt = null, $title = null, $library = null){
        return App::htmls()->createRelativeHtmlImage($this->getAssetResolver(), $location, $alt, $title, $library);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::getFilePreview()
     */
    public function getFilePreview($fileUrl, $fileLocation, $shortFileName, $useOriginalFileDetails = false, \muuska\util\upload\UploadInfo $uploadInfo = null){
        return $shortFileName;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::getHtmlRenderer()
     */
    public function getHtmlRenderer($componentName){
        return $this->isComponentAvailable($componentName) ? $this->createTemplate($this->getComponentTemplatePath($componentName)) : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::createTemplate()
     */
    public function createTemplate($relativePath, \muuska\translation\TemplateTranslator $translator = null, $innerPath = null){
        $baseTranslator = $translator;
        $innerTranslator = null;
        if(!empty($innerPath)){
            $innerTranslator = $translator;
            $baseTranslator = null;
        }
        if($baseTranslator === null){
            $baseTranslator = $this->getTranslator(App::translations()->createTemplateTranslationConfig($relativePath, false));
        }
        return App::renderers()->createPHPTemplate($relativePath, $this->getTemplateDir(), $baseTranslator, $innerPath, $innerTranslator);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::getCoreDir()
     */
    public function getCoreDir(){
        return App::getApp()->getRootDir().$this->corePath.'/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::getCommandClasses()
     */
    public function getCommandClasses($style, $secondStyle = null){
        $result = array('btn');
        $style = empty($style) ? ButtonStyle::SECONDARY : $style;
        $class = 'btn-';
        if(!empty($secondStyle)){
            $class .= (isset($this->tmpData['commandSecondStyles']) && isset($this->tmpData['commandSecondStyles'][$secondStyle])) ? $this->tmpData['commandSecondStyles'][$secondStyle] : $secondStyle;
            $class .= '-';
        }
        $class .= (isset($this->tmpData['commandStyles']) && isset($this->tmpData['commandStyles'][$style])) ? $this->tmpData['commandStyles'][$style] : $style;
        $result[] = $class;
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::getFinalCommandStyleClass()
     */
    public function getFinalCommandStyleClass($class){
        return (isset($this->tmpData['commandStyleClasses']) && isset($this->tmpData['commandStyleClasses'][$class])) ? $this->tmpData['commandStyleClasses'][$class] : $class;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::getFinalAlertStyleClass()
     */
    public function getFinalAlertStyleClass($class){
        return (isset($this->tmpData['alertStyleClasses']) && isset($this->tmpData['alertStyleClasses'][$class])) ? $this->tmpData['alertStyleClasses'][$class] : $class;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::getAlertClassesFromType()
     */
    public function getAlertClassesFromType($type, $style = null){
        $result = array('alert');
        $class = 'alert-';
        if(!empty($style)){
            $class .= isset($this->alertStyles[$style]) ? $this->alertStyles[$style] : $style;
            $class .= '-';
        }
        $class .= isset($this->alertTypes[$type]) ? $this->alertTypes[$type] : $type;
        $result[] = $class;
        return $result;
    }
    
    /**
     * @return \muuska\translation\TranslationStore
     */
    public function getTranslatorStore(){
        if(!isset($this->tmpData['translatorStore'])){
            $this->tmpData['translatorStore'] = App::translations()->createTranslationStore($this->getTranslationDirPattern(), false, null);
        }
        return $this->tmpData['translatorStore'];
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::getTranslator()
     */
    public function getTranslator(\muuska\translation\config\TranslatorConfig $translatorConfig, \muuska\translation\Translator $alternativeTranslator = null){
        $result = null;
        $translatorStore = $this->getTranslatorStore();
        if($translatorStore !== null){
            $result = $translatorStore->getTranslator($translatorConfig, $alternativeTranslator);
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::createJSTranslation()
     */
    public function createJSTranslation($lang, $location, \muuska\asset\AssetSetter $assetSetter = null, $defaultScopeEnabled = true, $priority = null, $locationInPage = null){
        $translatorStore = $this->getTranslatorStore();
        if ($translatorStore !== null) {
            $result = $translatorStore->createJSTranslation($lang, $location, false, null, $defaultScopeEnabled, $priority, $locationInPage);
            if($assetSetter !== null){
                $this->formatAssetTranslation($assetSetter, $result);
            }
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::formatAssetTranslation()
     */
    public function formatAssetTranslation(\muuska\asset\AssetSetter $assetSetter, \muuska\asset\AssetTranslation $assetTranslation, $innerScopes = array()){
        App::getTools()->formatAssetTranslation($this->getTranslationJsScopes(), $assetSetter, $assetTranslation);
    }
    
    /**
     * @return string[]
     */
    public function getTranslationJsScopes(){
        return array('theme');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::getSubPathInApp()
     */
    public function getSubPathInApp(){
        return FolderPath::ALL_THEMES . '/'. $this->name;
    }
    
    /**
     * @return string
     */
    public function getTranslationDirPattern(){
        return $this->getCoreDir().FolderPath::TRANSLATION.'/{lang}/';
    }
    
    /**
     * @return string
     */
    public function getTemplateDir(){
        return $this->getCoreDir().FolderPath::TEMPLATES.'/';
    }
    
    /**
     * @return string
     */
    public function getAssetPathPattern(){
        return FolderPath::ASSETS . '/'.$this->getSubPathInApp().'/{type}/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::formatControllerPage()
     */
    public function formatControllerPage(\muuska\controller\event\ControllerPageFormatingEvent $event){
        $event->autoFormatPage($this, $this->mainConfig, $this->getComponentTemplatePath('page'));
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::formatMailPage()
     */
    public function formatMailPage(\muuska\mail\event\MailPageFormatingEvent $event){
        $event->autoFormatPage($this, $this->mainConfig, $this->getComponentTemplatePath('page'));
    }
    
    /**
     * @param string $location
     * @param string $library
     * @param string $locationInPage
     * @return \muuska\asset\RelativeUriAsset
     */
    public function createCSS($location, $library = null, $locationInPage = null){
        return $this->createAsset(AssetType::CSS, $location, $library, AssetPriority::MAX, $locationInPage);
    }
    
    /**
     * @param string $location
     * @param string $library
     * @param string $locationInPage
     * @return \muuska\asset\RelativeUriAsset
     */
    public function createJS($location, $library = null, $locationInPage = null){
        return $this->createAsset(AssetType::JS, $location, $library, AssetPriority::MAX, $locationInPage);
    }
    
    /**
     * @param string $location
     * @param string $locationInPage
     * @return \muuska\asset\AbsoluteUriAsset
     */
    public function createAbsoluteCSS($location, $locationInPage = null){
        return App::assets()->createAbsoluteUriAsset(AssetType::CSS, $location, AssetPriority::MAX, $locationInPage);
    }
    
    /**
     * @param string $location
     * @param string $locationInPage
     * @return \muuska\asset\AbsoluteUriAsset
     */
    public function createAbsoluteJS($location, $locationInPage = null){
        return App::assets()->createAbsoluteUriAsset(AssetType::JS, $location, AssetPriority::MAX, $locationInPage);
    }

    /**
     * {@inheritDoc}
     * @see \muuska\util\theme\Theme::getWidthClass()
     */
    public function getWidthClass($class)
    {
        return (isset($this->tmpData['widthClasses']) && isset($this->tmpData['widthClasses'][$class])) ? $this->tmpData['widthClasses'][$class] : $class;
    }
}
