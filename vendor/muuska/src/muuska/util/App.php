<?php
namespace muuska\util;


use muuska\html\constants\AlertType;

class App
{
    /**
     * @var App
     */
    private static $instance;
    
    /**
     * @var \muuska\project\Application
     */
    private static $mainApplication;
    
    protected function __construct(){}
    
    public static function init(){
        if(self::$instance === null){
            self::$instance = new static();
        }
    }

    /**
	 * @return \muuska\instantiator\Assets
	 */
    protected function getAssets(){
	    return \muuska\instantiator\Assets::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Caches
	 */
	protected function getCaches(){
	    return \muuska\instantiator\Caches::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Daos
	 */
	protected function getDaos(){
	    return \muuska\instantiator\Daos::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Models
	 */
	protected function getModels(){
	    return \muuska\instantiator\Models::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Urls
	 */
	protected function getUrls(){
	    return \muuska\instantiator\Urls::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Utils
	 */
	protected function getUtils(){
	    return \muuska\instantiator\Utils::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Htmls
	 */
	protected function getHtmls(){
	    return \muuska\instantiator\Htmls::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Controllers
	 */
	protected function getControllers(){
	    return \muuska\instantiator\Controllers::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Validations
	 */
	protected function getValidations(){
	    return \muuska\instantiator\Validations::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Helpers
	 */
	protected function getHelpers(){
	    return \muuska\instantiator\Helpers::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Renderers
	 */
	protected function getRenderers(){
	    return \muuska\instantiator\Renderers::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Securities
	 */
	protected function getSecurities(){
	    return \muuska\instantiator\Securities::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Getters
	 */
	protected function getGetters(){
	    return \muuska\instantiator\Getters::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Options
	 */
	protected function getOptions(){
	    return \muuska\instantiator\Options::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Checkers
	 */
	protected function getCheckers(){
	    return \muuska\instantiator\Checkers::getInstance();
	}
	/**
	 * @return \muuska\instantiator\Configs
	 */
	protected function getConfigs(){
	    return \muuska\instantiator\Configs::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Translations
	 */
	protected function getTranslations(){
	    return \muuska\instantiator\Translations::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Mails
	 */
	protected function getMails(){
	    return \muuska\instantiator\Mails::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Https
	 */
	protected function getHttps(){
	    return \muuska\instantiator\Https::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Projects
	 */
	protected function getProjects(){
	    return \muuska\instantiator\Projects::getInstance();
	}
	
	/**
	 * @return \muuska\instantiator\Localizations
	 */
	protected function getLocalizations(){
	    return \muuska\instantiator\Localizations::getInstance();
	}
	
	
	/**
	 * @return \muuska\instantiator\Assets
	 */
	public static function assets(){
	    return self::$instance->getAssets();
	}
	
	/**
	 * @return \muuska\instantiator\Caches
	 */
	public static function caches(){
	    return self::$instance->getCaches();
	}
	
	/**
	 * @return \muuska\instantiator\Daos
	 */
	public static function daos(){
	    return self::$instance->getDaos();
	}
	
	/**
	 * @return \muuska\instantiator\Models
	 */
	public static function models(){
	    return self::$instance->getModels();
	}
	
	/**
	 * @return \muuska\instantiator\Urls
	 */
	public static function urls(){
	    return self::$instance->getUrls();
	}
	
	/**
	 * @return \muuska\instantiator\Utils
	 */
	public static function utils(){
	    return self::$instance->getUtils();
	}
	
	/**
	 * @return \muuska\instantiator\Htmls
	 */
	public static function htmls(){
	    return self::$instance->getHtmls();
	}
	
	/**
	 * @return \muuska\instantiator\Controllers
	 */
	public static function controllers(){
	    return self::$instance->getControllers();
	}
	
	/**
	 * @return \muuska\instantiator\Validations
	 */
	public static function validations(){
	    return self::$instance->getValidations();
	}
	
	/**
	 * @return \muuska\instantiator\Helpers
	 */
	public static function helpers(){
	    return self::$instance->getHelpers();
	}
	
	/**
	 * @return \muuska\instantiator\Renderers
	 */
	public static function renderers(){
	    return self::$instance->getRenderers();
	}
	
	/**
	 * @return \muuska\instantiator\Securities
	 */
	public static function securities(){
	    return self::$instance->getSecurities();
	}
	
	/**
	 * @return \muuska\instantiator\Getters
	 */
	public static function getters(){
	    return self::$instance->getGetters();
	}
	
	/**
	 * @return \muuska\instantiator\Options
	 */
	public static function options(){
	    return self::$instance->getOptions();
	}
	
	/**
	 * @return \muuska\instantiator\Checkers
	 */
	public static function checkers(){
	    return self::$instance->getCheckers();
	}
	/**
	 * @return \muuska\instantiator\Configs
	 */
	public static function configs(){
	    return self::$instance->getConfigs();
	}
	/**
	 * @return \muuska\instantiator\Translations
	 */
	public static function translations(){
	    return self::$instance->getTranslations();
	}
	/**
	 * @return \muuska\instantiator\Mails
	 */
	public static function mails(){
	    return self::$instance->getMails();
	}
	/**
	 * @return \muuska\instantiator\Https
	 */
	public static function https(){
	    return self::$instance->getHttps();
	}
	/**
	 * @return \muuska\instantiator\Projects
	 */
	public static function projects(){
	    return self::$instance->getProjects();
	}
	/**
	 * @return \muuska\instantiator\Localizations
	 */
	public static function localizations(){
	    return self::$instance->getLocalizations();
	}
	
	/**
	 * @return \muuska\project\Application
	 */
	public static function getApp()
	{
	    return self::$mainApplication;
	}
	
	/**
	 * @param \muuska\project\Application $mainApplication
	 * @throws \Exception
	 */
	final public static function setMainApplication(\muuska\project\Application $mainApplication)
	{
	    if(self::$mainApplication === null){
	        self::$mainApplication = $mainApplication;
	    }else{
	        throw new \Exception('Main application is already initialized');
	    }
	}
	
	/**
	 * @return bool
	 */
	public static function isMainApplicationInitialized(){
	    return (self::$mainApplication !== null);
	}
	
	/**
	 * @return \muuska\util\event\EventTrigger
	 */
	public static function getEventTrigger()
	{
	    return self::getApp()->getEventTrigger();
	}
	
	/**
	 * @return \muuska\cache\CacheManager
	 */
	public static function getCacheManager()
	{
	    return self::getApp()->getCacheManager();
	}
	
	/**
	 * @return \muuska\project\Project
	 */
	public static function getFramework()
	{
	    return self::getApp()->getFrameworkInstance();
	}
	
	/**
	 * @return \muuska\util\tool\StringTools
	 */
	public static function getStringTools()
	{
	    return self::utils()->getStringToolsInstance();
	}
	
	/**
	 * @return \muuska\util\tool\FileTools
	 */
	public static function getFileTools()
	{
	    return self::utils()->getFileToolsInstance();
	}
	
	/**
	 * @return \muuska\util\tool\Tools
	 */
	public static function getTools()
	{
	    return self::utils()->getToolsInstance();
	}
	
	/**
	 * @return \muuska\util\tool\ArrayTools
	 */
	public static function getArrayTools()
	{
	    return self::utils()->getArrayToolsInstance();
	}
	
	/**
	 * @return \muuska\validation\ValidationRuleManager
	 */
	public static function getValidationRuleManager()
	{
	    return self::validations()->getValidationRuleManagerInstance();
	}
	
	/**
	 * @param \muuska\project\Project $project
	 * @param \muuska\translation\config\TranslatorConfig $translatorConfig
	 * @param string $subAppName
	 * @param \muuska\translation\Translator $alternativeTranslator
	 * @param bool $createSameIfNotFound
	 * @return \muuska\translation\Translator
	 */
	public static function getProjectTranslator(\muuska\project\Project $project, \muuska\translation\config\TranslatorConfig $translatorConfig, $subAppName = null, \muuska\translation\Translator $alternativeTranslator = null, $createSameIfNotFound = false)
	{
	    $result = null;
	    if(empty($subAppName)){
	        $result = $project->getTranslator($translatorConfig, $alternativeTranslator);
	    }elseif ($project->hasSubProject($subAppName)){
	        $result = $project->getSubProject($subAppName)->getTranslator($translatorConfig, $alternativeTranslator);
	    }
	    if($createSameIfNotFound && ($result === null)){
	        $result = self::translations()->createSameTranslator();
	    }
	    return $result;
	}
	
	/**
	 * @param \muuska\translation\config\TranslatorConfig $translatorConfig
	 * @param string $subAppName
	 * @param \muuska\translation\Translator $alternativeTranslator
	 * @param bool $createSameIfNotFound
	 * @return \muuska\translation\Translator
	 */
	public static function getAppTranslator(\muuska\translation\config\TranslatorConfig $translatorConfig, $subAppName = null, \muuska\translation\Translator $alternativeTranslator = null, $createSameIfNotFound = false)
	{
	    return self::getProjectTranslator(self::getApp(), $translatorConfig, $subAppName, $alternativeTranslator, $createSameIfNotFound);
	}
	
	/**
	 * @param \muuska\translation\config\TranslatorConfig $translatorConfig
	 * @param string $subAppName
	 * @param \muuska\translation\Translator $alternativeTranslator
	 * @param bool $createSameIfNotFound
	 * @return \muuska\translation\Translator
	 */
	public static function getFrameworkTranslator(\muuska\translation\config\TranslatorConfig $translatorConfig, $subAppName = null, \muuska\translation\Translator $alternativeTranslator = null, $createSameIfNotFound = false)
	{
	    return self::getProjectTranslator(self::getFramework(), $translatorConfig, $subAppName, $alternativeTranslator, $createSameIfNotFound);
	}
	
	/**
	 * @param \muuska\translation\config\TranslatorConfig $translatorConfig
	 * @param string $string
	 * @param string $lang
	 * @param string $context
	 * @param string $subAppName
	 * @param \muuska\translation\Translator $alternativeTranslator
	 * @return string
	 */
	public static function translateFramework(\muuska\translation\config\TranslatorConfig $translatorConfig, $string, $lang, $context = null, $subAppName = null, \muuska\translation\Translator $alternativeTranslator = null)
	{
	    return self::translateProject(self::getFramework(), $translatorConfig, $string, $lang, $subAppName, $context, $alternativeTranslator);
	}
	
	/**
	 * @param \muuska\translation\config\TranslatorConfig $translatorConfig
	 * @param string $string
	 * @param string $lang
	 * @param string $subAppName
	 * @param string $context
	 * @param \muuska\translation\Translator $alternativeTranslator
	 * @return string
	 */
	public static function translateApp(\muuska\translation\config\TranslatorConfig $translatorConfig, $string, $lang, $subAppName = null, $context = null, \muuska\translation\Translator $alternativeTranslator = null)
	{
	    return self::translateProject(self::getApp(), $translatorConfig, $string, $lang, $subAppName, $context, $alternativeTranslator);
	}
	
	/**
	 * @param \muuska\project\Project $project
	 * @param \muuska\translation\config\TranslatorConfig $translatorConfig
	 * @param string $string
	 * @param string $lang
	 * @param string $subAppName
	 * @param string $context
	 * @param \muuska\translation\Translator $alternativeTranslator
	 * @return string
	 */
	public static function translateProject(\muuska\project\Project $project, \muuska\translation\config\TranslatorConfig $translatorConfig, $string, $lang, $subAppName = null, $context = null, \muuska\translation\Translator $alternativeTranslator = null)
	{
	    $result = $string;
	    $translator = self::getProjectTranslator($project, $translatorConfig, $subAppName, $alternativeTranslator);
	    if($translator !== null){
	        $result = $translator->translate($lang, $string, $context);
	    }
	    return $result;
	}
	
	/**
	 * @param string $subAppName
	 * @return \muuska\util\theme\Theme
	 */
	public static function getTheme($subAppName = null)
	{
	    $subAppName = empty($subAppName) ? self::getDefaultSubAppName() : $subAppName;
	    return self::getApp()->hasSubApplication($subAppName) ? self::getApp()->getSubApplication($subAppName)->getActiveTheme() : null;
	}
	
	/**
	 * @return string
	 */
	public static function getDefaultSubAppName()
	{
	    return self::getArrayTools()->getFirstValue(self::getApp()->getEnabledSubApplications());
	}
	
	/**
	 * @param string $lang
	 * @param string $subAppName
	 * @param \muuska\asset\AssetOutputConfig $assetOutputConfig
	 * @param \muuska\util\theme\Theme $theme
	 * @param \muuska\asset\AssetSetter $assetSetter
	 * @return \muuska\html\config\DefaultHtmlGlobalConfig
	 */
	public static function createHtmlGlobalConfig($lang = null, $subAppName = null, \muuska\asset\AssetOutputConfig $assetOutputConfig = null, \muuska\asset\AssetSetter $assetSetter = null, \muuska\util\theme\Theme $theme = null){
	    $lang = empty($lang) ? App::getApp()->getDefaultLang() : $lang;
	    $theme = ($theme === null) ? self::getTheme($subAppName) : $theme;
	    $assetSetter = ($assetSetter === null) ? self::assets()->createDefaultAssetSetter() : $assetSetter;
	    return self::htmls()->createDefaultHtmlGlobalConfig($lang, $assetSetter, $theme, $assetOutputConfig);
	}
	
	/**
	 * @return bool
	 */
	public static function isDevMode() {
	    return self::getApp()->isDevMode();
	}
	
	/**
	 * @param string $string
	 * @param string $name
	 * @return \muuska\html\HtmlString
	 */
	public static function createHtmlString($string, $name = null) {
	    return self::htmls()->createHtmlString($string, $name);
	}
	
	/**
	 * @param string $string
	 * @param string $name
	 * @return \muuska\html\HtmlLabel
	 */
	public static function createHtmlLabel($string, $name = null) {
	    return self::htmls()->createHtmlLabel(self::createHtmlString($string), $name);
	}
	
	/**
	 * @param string $value
	 * @return \muuska\html\icon\ClassIcon
	 */
	public static function createFAIcon($value) {
	    return self::htmls()->createClassIcon('fa fa-'.$value);
	}
	
	/**
	 * @return \muuska\translation\config\AlertTranslationConfig
	 */
	public static function createErrorTranslationConfig() {
	    return self::translations()->createAlertTranslationConfig(AlertType::DANGER);
	}
}
