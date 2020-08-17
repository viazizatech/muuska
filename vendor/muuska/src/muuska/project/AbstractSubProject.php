<?php
namespace muuska\project;

use muuska\asset\constants\AssetNames;
use muuska\constants\FolderPath;
use muuska\constants\Names;
use muuska\util\AbstractExtraDataProvider;
use muuska\util\App;

class AbstractSubProject extends AbstractExtraDataProvider implements SubProject
{
    /**
     * @var Project
     */
    protected $project;
    
    private $tmpData;
    
    /**
     * @var string
     */
    protected $subAppName;
    
    /**
     * @var bool
     */
    protected $relatedToTheme;
    
    /**
     * @param string $subAppName
     * @param Project $project
     */
    public function __construct($subAppName, Project $project) {
        $this->subAppName = $subAppName;
        $this->project = $project;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\SubProject::isThemeImplemented()
     */
    public function isThemeImplemented($themeName){
        return false;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\SubProject::createTemplate()
     */
    public function createTemplate($relativePath, \muuska\translation\TemplateTranslator $translator = null, $innerPath = null, $relatedToTheme = null){
        $baseTranslator = $translator;
        $innerTranslator = null;
        if(!empty($innerPath)){
            $innerTranslator = $translator;
            $baseTranslator = null;
        }
        if($baseTranslator === null){
            $baseTranslator = $this->getTranslator(App::translations()->createTemplateTranslationConfig($relativePath, $relatedToTheme));
        }
        $basePath = $this->project->getTemplateDir().$this->getSubAppSubFolderPath().($this->isRelatedToTheme() ? $this->getThemeRelativePath($relatedToTheme) : '');
        return App::renderers()->createPHPTemplate($relativePath, $basePath, $baseTranslator, $innerPath, $innerTranslator);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\SubProject::getActiveThemeName()
     */
    public function getActiveThemeName(){
        $name = Names::DEFAULT_THEME;
        $subApplication = App::getApp()->getSubApplication($this->subAppName);
        if($subApplication !== null){
            $configuredName = $subApplication->getConfiguredThemeName();
            if ($this->isThemeImplemented($configuredName)) {
                $name = $configuredName;
            }
        }
        return $name;
    }
    
    /**
     * @param bool $relatedToTheme
     * @return string
     */
    public function getThemeRelativePath($relatedToTheme){
        return $relatedToTheme ? (FolderPath::ALL_THEMES . '/'.$this->getActiveThemeName().'/') : '';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\SubProject::createController()
     */
    public function createController(\muuska\controller\ControllerInput $input){
        
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\SubProject::getAssetResolver()
     */
    public function getAssetResolver($relatedToTheme = null){
        $key = (int)$relatedToTheme;
        if(!isset($this->tmpData['assetResolvers']) || !isset($this->tmpData['assetResolvers'][$key])){
            $subPattern = $this->project->getAssetPathPattern().$this->getSubAppSubFolderPath().$this->getThemeRelativePath($relatedToTheme);
            $this->tmpData['assetResolvers'][$key] = App::assets()->createDefaultRelativeAssetResolver(App::getApp()->getBaseUrl().$subPattern, App::getApp()->getRootDir().$subPattern);
        }
        return $this->tmpData['assetResolvers'][$key];
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\SubProject::createAssetGroup()
     */
    public function createAssetGroup($name, \muuska\asset\AssetSetter $assetSetter){
        $group = null;
        if($name === AssetNames::SUB_PROJECT_DEFAULT_GROUP){
            $group = $this->createDefaultAssetGroup($name, $assetSetter);
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
     * {@inheritDoc}
     * @see \muuska\project\SubProject::createRelativeAsset()
     */
    public function createAsset($assetType, $location, $library = null, $priority = null, $locationInPage = null, $relatedToTheme = null){
        return App::assets()->createRelativeUriAsset($assetType, $location, $this->getAssetResolver($relatedToTheme), $library, $priority, $locationInPage);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\SubProject::createHtmlImage()
     */
    public function createHtmlImage($location, $alt = null, $title = null, $library = null, $relatedToTheme = null){
        return App::htmls()->createRelativeHtmlImage($this->getAssetResolver($relatedToTheme), $location, $alt, $title, $library);
    }
    
    /**
     * @return \muuska\translation\TranslationStore
     */
    public function getTranslatorStore(){
        if(!isset($this->tmpData['translatorStore'])){
            $baseDir = $this->project->getTranslationDirPattern().$this->getSubAppSubFolderPath();
            $relatedToTheme = $this->isRelatedToTheme();
            $this->tmpData['translatorStore'] = App::translations()->createTranslationStore($baseDir, $relatedToTheme, ($relatedToTheme ? $this->getActiveThemeName() : null));
        }
        return $this->tmpData['translatorStore'];
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\SubProject::getTranslator()
     */
    public function getTranslator(\muuska\translation\config\TranslatorConfig $translatorConfig, \muuska\translation\Translator $alternativeTranslator = null){
        $translatorStore = $this->getTranslatorStore();
        return ($translatorStore !== null) ? $translatorStore->getTranslator($translatorConfig, $alternativeTranslator) : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\SubProject::createJSTranslation()
     */
    public function createJSTranslation($lang, $location, \muuska\asset\AssetSetter $assetSetter = null, $defaultScopeEnabled = true, $priority = null, $locationInPage = null, $relatedToTheme = null){
        $result = null;
        $translatorStore = $this->getTranslatorStore();
        if ($translatorStore !== null) {
            $result = $translatorStore->createJSTranslation($lang, $location, null, $defaultScopeEnabled, $priority, $locationInPage, $relatedToTheme);
            if($assetSetter !== null){
                $this->formatAssetTranslation($assetSetter, $result);
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\SubProject::formatAssetTranslation()
     */
    public function formatAssetTranslation(\muuska\asset\AssetSetter $assetSetter, \muuska\asset\AssetTranslation $assetTranslation, $innerScopes = array()){
        App::getTools()->formatAssetTranslation($this->getTranslationJsScopes(), $assetSetter, $assetTranslation);
    }
    
    /**
     * @return string[]
     */
    public function getTranslationJsScopes(){
        $result = $this->project->getSubFoldersInApp();
        $folder = $this->getSubAppSubFolder();
        if(!empty($folder)){
            $result[] = $folder;
        }
        return $result;
    }
    
    /**
     * @return string
     */
    public function getSubAppSubFolder()
    {
        return strtolower($this->subAppName);
    }
    
    /**
     * @return string
     */
    public function getSubAppSubFolderPath()
    {
        return $this->getSubAppSubFolder().'/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\SubProject::isRelatedToTheme()
     */
    public function isRelatedToTheme(){
        return $this->relatedToTheme;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\url\event\UrlCreationEventListener::onUrlCreation()
     */
    public function onUrlCreation(\muuska\url\event\UrlCreationEvent $event)
    {}
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\event\ControllerEventListener::onControllerPageFormating()
     */
    public function onControllerPageFormating(\muuska\controller\event\ControllerPageFormatingEvent $event)
    {}
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\event\ControllerEventListener::onControllerOtherEvent()
     */
    public function onControllerOtherEvent($code, \muuska\controller\event\ControllerEvent $event)
    {}
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\event\ControllerEventListener::onControllerActionProcessing()
     */
    public function onControllerActionProcessing($code, \muuska\controller\event\ControllerActionProcessingEvent $event)
    {}
}
