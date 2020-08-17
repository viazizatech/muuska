<?php
namespace muuska\controller;

use muuska\http\constants\RedirectionType;
use muuska\util\App;
use muuska\constants\ActionCode;
use muuska\asset\constants\AssetType;
use muuska\asset\constants\AssetNames;
use muuska\constants\Names;
use muuska\project\constants\ProjectType;

abstract class AbstractController implements Controller
{
    /**
	 * @var DefaultControllerResult
	 */
    protected $result;
	
    /**
     * @var bool
     */
    protected $authentificationAlwaysRequired = false;
    
    /**
     * @var bool
     */
    protected $accessCheckingAlwaysRequired = false;
    
	/**
	 * @var array
	 */
	protected $dataUsedOnce = array();
	
	/**
	 * @var \muuska\url\ControllerUrlCreator
	 */
	protected $urlCreator;
	
	/**
	 * @var array
	 */
	protected $actionDefinitions = array();
	
	/**
	 * @var array
	 */
	protected $successLabels = array();
	
	/**
	 * @var array
	 */
	protected $errorLabels = array();
	
	/**
	 * @var \muuska\controller\ControllerInput
	 */
	protected $input;
	
	/**
	 * @var \muuska\translation\ControllerTranslator
	 */
	protected $translator;
	
	/**
	 * @var bool
	 */
	protected $relatedToTheme = true;
	
	/**
	 * @var \muuska\controller\param\ControllerParamResolver
	 */
	protected $paramResolver;
	
	/**
	 * @param ControllerInput $input
	 */
	public function __construct(ControllerInput $input)
	{
	    $this->input = $input;
	    $this->createResult();
	    $this->createTranslator();
	    $this->onCreate();
	}
	
	protected function onCreate(){}
	
	protected function createTranslator(){
	    $alternativeTranslator = App::getFramework()->getTranslator(App::translations()->createCustomTranslationConfig('abstract_controller'));
	    $this->translator = $this->input->getSubProject()->getTranslator(App::translations()->createControllerTranslationConfig($this->input->getName(), $this->input->getSubProject()->isRelatedToTheme()), $alternativeTranslator);
	}
	
	protected function createResult(){
	    $this->result = App::controllers()->createDefaultControllerResult(App::assets()->createDefaultAssetSetter());
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\Controller::isAuthentificationAlwaysRequired()
	 */
	public function isAuthentificationAlwaysRequired(){
	    return $this->authentificationAlwaysRequired;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\Controller::isAccessCheckingAlwaysRequired()
	 */
	public function isAccessCheckingAlwaysRequired(){
	    return $this->accessCheckingAlwaysRequired;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\Controller::onUserNotLogged()
	 */
	public function onUserNotLogged(){
	    if($this->urlCreator === null){
	        $this->urlCreator = App::urls()->createDefaultControllerUrl($this->input);
	    }
	    if($this->paramResolver === null){
	        $this->paramResolver = App::controllers()->createDefaultControllerParamResolver($this->input, $this->result);
	    }
	    $redirection = App::https()->createDynamicRedirection(RedirectionType::LOGIN);
	    $projectType = $this->input->getProject()->getType();
	    if(($this->input->getName() !== Names::HOME_CONTROLLER) || ($projectType === ProjectType::MODULE)){
	        $backRedirection = App::https()->createDirectRedirection(RedirectionType::BACK_TO_CALLER, $this->input->getRequest()->getRequestURL(true, true));
	        $redirection->setBackRedirection($backRedirection);
	    }
	    $this->result->setRedirection($redirection);
	    if($this->input->isOutputEnabled()){
	        $this->outputResult(false, null);
	    }
	    return $this->result;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\Controller::checkSecurityAccess()
	 */
	public function checkSecurityAccess(){
	    return true;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\Controller::executeAction()
	 */
	public function executeAction(){
	    $this->init();
	    $action = $this->input->getAction();
	    $cacheEnabled = false;
	    $cacheKey = '';
	    if($this->input->isOutputEnabled() && isset($this->actionDefinitions[$action]) && isset($this->actionDefinitions[$action]['cacheEnabled']) && $this->actionDefinitions[$action]['cacheEnabled']){
	        $cacheEnabled = true;
	        $cacheKey = 'controllers/'.$this->input->getLang().'/'.$this->input->getFullName().'/'.$action.'/'.(int)$this->input->getRequestType().'/';
	        $innerCacheKey = $this->getInnerCacheKey();
	        if(empty($innerCacheKey)){
	            $innerCacheKey = '0';
	        }
	        $cacheKey .= md5($innerCacheKey);
	    }
	    if(!$cacheEnabled || !App::getCacheManager()->isStored($cacheKey)){
	        if(!$this->result->hasErrors() && !$this->result->hasRedirection()){
	            $event = App::controllers()->createControllerActionProcessingEvent($this, $this->input, $this->urlCreator, $this->result, $this->paramResolver);
	            if(App::getEventTrigger()->fireControllerActionProcessing('before', $event)){
	                $methodName = 'process'.App::getStringTools()->toCamelCase($action, true, '-');
	                $this->initTitle();
	                $this->retrieveAlertsFromUrl();
	                $this->retrieveDataUsedOnce();
	                if(method_exists($this, $methodName)){
	                    $this->$methodName();
	                    App::getEventTrigger()->fireControllerActionProcessing('after', $event->createAfterEvent());
	                }else{
	                    $this->result->addError($this->input->getFrameworkError('The action you specified does not exist'));
	                }
	            }
	        }
	        if($this->input->isOutputEnabled()){
	            $this->outputResult($cacheEnabled, $cacheKey);
	        }
	    }else{
	        $response = $this->input->getResponse();
	        $response->setBody(App::getCacheManager()->retrieve($cacheKey));
	        $response->send();
	    }
	    
	    return $this->result;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\Controller::getInput()
	 */
	public function getInput(){
	    return $this->input;
	}
	
	protected function initTitle() {
	    $action = $this->input->getAction();
	    $title = $this->l($this->input->getName(), 'controller_title');
	    if($action !== ActionCode::DEFAULT_PROCESS){
	        $title .= ' : ' . $this->l($action, 'action_title');
	        
	    }
	    $this->result->setTitle($title);
	}
	
	/**
	 * @return string
	 */
	protected function getInnerCacheKey() {
	    $action = $this->input->getAction();
	    $innerCacheKey = '';
	    if($this->actionDefinitions[$action]['cacheKeyCreator']){
	        $innerCacheKey = call_user_func($this->actionDefinitions[$action]['cacheKeyCreator']);
	    }else{
	        $innerCacheKey = $this->input->getRequest()->getQueryString().implode('_', $this->input->getPathParams());
	    }
	    return $innerCacheKey;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\Controller::onAccessFailed()
	 */
	public function onAccessFailed(){
	    if($this->urlCreator === null){
	        $this->urlCreator = App::urls()->createDefaultControllerUrl($this->input);
	    }
	    if($this->paramResolver === null){
	        $this->paramResolver = App::controllers()->createDefaultControllerParamResolver($this->input, $this->result);
	    }
	    $this->result->addError($this->input->getFrameworkError('You do not have permission to access this resource'));
	    if($this->input->isOutputEnabled()){
	        $this->outputResult(false, null);
	    }
	    return $this->result;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\controller\Controller::onSecurityFailed()
	 */
	public function onSecurityFailed(){
	    if($this->urlCreator === null){
	        $this->urlCreator = App::urls()->createDefaultControllerUrl($this->input);
	    }
	    if($this->paramResolver === null){
	        $this->paramResolver = App::controllers()->createDefaultControllerParamResolver($this->input, $this->result);
	    }
	    $this->result->addError($this->input->getFrameworkError('Security access failed'));
	    return $this->result;
	}
	
	protected function init()
    {
        $this->initParamResolver();
        if($this->urlCreator === null){
            $this->urlCreator = $this->paramResolver->createUrlCreator($this->input, $this->result);
        }
        if(!$this->result->hasErrors()){
            $this->initSuccessLabels();
            $this->initErrorLabels();
        }
    }
	
    protected function initParamResolver()
    {
        $this->paramResolver = App::controllers()->createDefaultControllerParamResolver($this->input, $this->result);
    }
	
	protected function processDefault(){}
	
	protected function initSuccessLabels(){}
	
	protected function initErrorLabels(){}
	
	/**
	 * @param string $code
	 * @return string
	 */
	protected function getSuccessLabel($code)
    {
		return isset($this->successLabels[$code]) ? $this->successLabels[$code] : $this->l($code, 'success_label');
	}
	
	/**
	 * @param string $code
	 * @return string
	 */
	protected function getErrorLabel($code)
	{
	    return isset($this->errorLabels[$code]) ? $this->errorLabels[$code] : $this->l($code, 'error_label');
	}
	
	/**
	 * @param bool $relatedToTheme
	 * @return \muuska\renderer\template\Template
	 */
	protected function createSingleTemplate($relatedToTheme = null)
	{
	    $path = 'controllers/'.$this->input->getName();
	    return $this->createTemplate($path, $this->getTemplateTranslator($this->input->getName(), $relatedToTheme), null, $relatedToTheme);
	}
	
	/**
	 * @param string $relativePath
	 * @param bool $relatedToTheme
	 * @return \muuska\renderer\template\Template
	 */
	protected function createInnerTemplate($relativePath, $relatedToTheme = null)
	{
	    $folder = 'controllers/'.$this->input->getName().'/';
	    return $this->createTemplate($relativePath, $this->getTemplateTranslator($relativePath, $relatedToTheme), $folder, $relatedToTheme);
	}
	
	/**
	 * @param string $relativePath
	 * @param bool $relatedToTheme
	 * @return \muuska\translation\TemplateTranslator
	 */
	protected function getTemplateTranslator($relativePath, $relatedToTheme = null)
	{
	    $relatedToTheme = ($relatedToTheme === null) ? $this->input->getSubProject()->isRelatedToTheme() : $relatedToTheme;
	    return ($this->translator !== null) ? $this->translator->getTemplateTranslator($relativePath, $relatedToTheme) : null;
	}
	
	/**
	 * @param string $relativePath
	 * @param \muuska\translation\TemplateTranslator $translator
	 * @param string $innerPath
	 * @param bool $relatedToTheme
	 */
	protected function createTemplate($relativePath, \muuska\translation\TemplateTranslator $translator = null, $innerPath = null, $relatedToTheme = null){
	    return $this->input->getSubProject()->createTemplate($relativePath, $translator, $innerPath, $relatedToTheme);
	}
	
	/**
	 * @param string $location
	 * @param string $alt
	 * @param string $title
	 * @param string $library
	 * @param bool $relatedToTheme
	 * @return \muuska\html\RelativeHtmlImage
	 */
	protected function createRelativeImage($location, $alt = null, $title = null, $library = null, $relatedToTheme = null){
	    return $this->input->getSubProject()->createHtmlImage($location, $alt, $title, $library, $relatedToTheme);
	}
	
	/**
	 * @param boolean $addTranslation
	 * @param int $priority
	 * @param string $locationInPage
	 * @param bool $relatedToTheme
	 * @return \muuska\asset\RelativeUriAsset
	 */
	protected function addSingleJS($addTranslation = false, $priority = null, $locationInPage = null, $relatedToTheme = null)
	{
	    if($addTranslation){
	        $loader = $this->getJSTranslationLoader($this->input->getName(), $relatedToTheme);
	        if ($loader !== null) {
	            $translationAsset = App::assets()->createAssetTranslation($loader, $this->input->getName(), $this->input->getLang());
	            $this->input->getSubProject()->formatAssetTranslation($this->result->getAssetSetter(), $translationAsset, array('controllers'));
	            $this->result->getAssetSetter()->appendAssetToContainer(AssetNames::TRANSLATION_CONTAINER, $translationAsset);
	        }
	    }
	    $asset = $this->input->getSubProject()->createAsset(AssetType::JS, 'controllers/'.$this->input->getName().'.js', null, $priority, $locationInPage, $relatedToTheme);
	    $this->result->getAssetSetter()->addAsset($asset);
	    return $asset;
	}
	
	/**
	 * @param string $relativePath
	 * @param boolean $addTranslation
	 * @param int $priority
	 * @param string $locationInPage
	 * @param bool $relatedToTheme
	 * @return \muuska\asset\RelativeUriAsset
	 */
	protected function addInnerJS($relativePath, $addTranslation = false, $priority = null, $locationInPage = null, $relatedToTheme = null)
	{
	    if($addTranslation){
	        $loader = $this->getJSTranslationLoader($this->input->getName(), $relatedToTheme);
	        if ($loader !== null) {
	            $translationAsset = App::assets()->createAssetTranslation($loader, $relativePath, $this->input->getLang());
	            $this->input->getSubProject()->formatAssetTranslation($this->result->getAssetSetter(), $translationAsset, array('controllers', $this->input->getName()));
	            $this->result->getAssetSetter()->appendAssetToContainer(AssetNames::TRANSLATION_CONTAINER, $translationAsset);
	        }
	    }
	    $asset = $this->input->getSubProject()->createAsset(AssetType::JS, 'controllers/'.$this->input->getName().'/'.$relativePath.'.js', null, $priority, $locationInPage, $relatedToTheme);
	    $this->result->getAssetSetter()->addAsset($asset);
	    return $asset;
	}
	
	/**
	 * @param string $relativePath
	 * @param bool $relatedToTheme
	 * @return \muuska\translation\loader\TranslationLoader
	 */
	protected function getJSTranslationLoader($relativePath, $relatedToTheme = null)
	{
	    $relatedToTheme = ($relatedToTheme === null) ? $this->input->getSubProject()->isRelatedToTheme() : $relatedToTheme;
	    return ($this->translator !== null) ? $this->translator->getJsTranslationLoader($relativePath) : null;
	}
	
	/**
	 * @param int $priority
	 * @param string $locationInPage
	 * @param bool $relatedToTheme
	 * @return \muuska\asset\RelativeUriAsset
	 */
	protected function addSingleCSS($priority = null, $locationInPage = null, $relatedToTheme = null)
	{
	    $asset = $this->input->getSubProject()->createAsset(AssetType::CSS, 'controllers/'.$this->input->getName().'.css', null, $priority, $locationInPage, $relatedToTheme);
	    $this->result->getAssetSetter()->addAsset($asset);
	    return $asset;
	}
	
	/**
	 * @param string $relativePath
	 * @param int $priority
	 * @param string $locationInPage
	 * @param bool $relatedToTheme
	 * @return \muuska\asset\RelativeUriAsset
	 */
	protected function addInnerCSS($relativePath, $priority = null, $locationInPage = null, $relatedToTheme = null)
	{
	    $asset = $this->input->getSubProject()->createAsset(AssetType::CSS, 'controllers/'.$this->input->getName().'/'.$relativePath.'.css', null, $priority, $locationInPage, $relatedToTheme);
	    $this->result->getAssetSetter()->addAsset($asset);
	    return $asset;
	}
	
	/**
	 * @param \muuska\html\areacreator\AreaCreator $areaCreator
	 * @param \muuska\renderer\HtmlContentRenderer $renderer
	 * @param boolean $setAsMainContent
	 * @return \muuska\html\HtmlCustomElement
	 */
	protected function createPageContent(\muuska\html\areacreator\AreaCreator $areaCreator = null, \muuska\renderer\HtmlContentRenderer $renderer = null, $setAsMainContent = true)
	{
	    $content = App::htmls()->createHtmlCustomElement($areaCreator, $renderer);
	    if($setAsMainContent){
	        $this->result->setContent($content);
	    }
	    return $content;
	}
    
	protected function getDataUsedOncePrefix()
	{
	    return $this->getRecorderPrefix().'_data_used_once';
	}
	
    protected function getRecorderPrefix()
    {
        return 'controller_'.$this->input->getFullName();
    }
    
    /**
     * @param string $key
     * @param string $value
     */
    protected function addDataUsedOnce($key, $value)
    {
        $this->dataUsedOnce[$key] = $value;
    }
    
	protected function saveDataUsedOnce()
    {
	    $visitorInfoRecorder = $this->input->getVisitorInfoRecorder();
	    $prefix = $this->getDataUsedOncePrefix();
	    $visitorInfoRecorder->removeValuesByPrefix($prefix);
		foreach($this->dataUsedOnce as $key => $data){
		    $visitorInfoRecorder->setValue($prefix.$key, $data);
		}
    }
    protected function retrieveAlertsFromUrl()
    {
        if ($this->input->hasQueryParam('success')) {
            $this->result->addSuccess($this->getSuccessLabel($this->input->getQueryParam('success')));
        }
        if ($this->input->hasQueryParam('error')) {
            $this->result->addError($this->getSuccessLabel($this->input->getQueryParam('error')));
        }
    }
    protected function retrieveDataUsedOnce()
    {
        $visitorInfoRecorder = $this->input->getVisitorInfoRecorder();
        $prefix = $this->getDataUsedOncePrefix().'alert_';
        $succesKey = $prefix.'success';
        if($visitorInfoRecorder->hasValue($succesKey)){
            $this->result->addSuccess($this->getSuccessLabel($visitorInfoRecorder->getValue($succesKey)));
        }
        
        $errorKey = $prefix.'error';
        if($visitorInfoRecorder->hasValue($errorKey)){
            $this->result->addError($this->getErrorLabel($visitorInfoRecorder->getValue($errorKey)));
        }
    }
    
    /**
     * @param bool $cacheEnabled
     * @param string $cacheKey
     */
    protected function outputResult($cacheEnabled, $cacheKey)
    {
        $content = '';
        $response = $this->input->getResponse();
        
        $this->saveDataUsedOnce();
        if($this->input->isAjaxRequest()){
            $content = $this->renderJsonResult();
        }else{
            if($this->result->hasRedirection()){
                $this->result->getRedirection()->redirect($this->createRedirectionInput());
            }else{
                $content = $this->renderHtmlPage();
            }
        }
        if($cacheEnabled){
            App::getCacheManager()->store($cacheKey, $content);
        }
        
        $response->setBody($content);
        $response->send();
    }
    
	/**
	 * @return string
	 */
	protected function renderHtmlPage()
	{
	    $htmlPage = App::htmls()->createHtmlPage($this->result->getTitle(), App::getTools()->createPageAreaCreator($this->result->getContent(), $this->result->getAllAlerts()));
	    $langInfo = $this->input->getLanguageInfo();
	    if($langInfo !== null){
	        $htmlPage->setLangIso($langInfo->getLanguage());
	    }
	    $htmlPage->addBodyClass('page-'.$this->input->getName());
	    $htmlPage->addBodyAttribute('project-type', $this->input->getProject()->getType());
	    $projectName = $this->input->getProject()->getName();
	    if(!empty($projectName)){
	        $htmlPage->addBodyAttribute('project-name', $projectName);
	    }
	    $action = $this->input->getAction();
	    if($action !== ActionCode::DEFAULT_PROCESS){
	        $htmlPage->addBodyAttribute('action', $action);
	    }
	    if($this->urlCreator === null){
	        $this->urlCreator = App::urls()->createDefaultControllerUrl($this->input);
	    }
	    if($this->paramResolver === null){
	        $this->paramResolver = App::controllers()->createDefaultControllerParamResolver($this->input, $this->result);
	    }
	    $event = App::controllers()->createControllerPageFormatingEvent($this, $htmlPage, $htmlPage->getAreaCreator(), $this->input, $this->urlCreator, $this->result, $this->paramResolver);
	    $this->formatHtmlPage($event);
	    return $this->renderHtmlContent($htmlPage);
	}
	
	/**
	 * @param \muuska\html\HtmlContent $htmlContent
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return string
	 */
	protected function renderHtmlContent(\muuska\html\HtmlContent $htmlContent, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null)
	{
	    return $htmlContent->generate($this->createHtmlGlobalConfig(), $callerConfig);
	}
	
	/**
	 * @param \muuska\controller\event\ControllerPageFormatingEvent $event
	 * @param bool $fireFormattingEvent
	 */
	protected function formatHtmlPage(\muuska\controller\event\ControllerPageFormatingEvent $event, $fireFormattingEvent = true)
	{
	    $event->autoAddAssets();
	    if($fireFormattingEvent && !$event->isDefaultPrevented() && !$event->isPropagationStopped()){
	        App::getEventTrigger()->fireControllerPageFormating($event);
	    }
	}
	
	/**
	 * @return string
	 */
	protected function renderJsonResult()
    {
	    $array = array();
        $extraData = $this->result->getAllExtra();
        if(!empty($extraData)){
            $array['extra'] = $extraData;
        }
        if($this->result->hasContent()){
            $array['content'] = $this->renderHtmlContent($this->result->getContent());
        }
        $array['hasErrors'] = $this->result->hasErrors();
        $alerts = $this->result->getAllAlerts();
        if(!empty($alerts)){
            $array['alerts'] = $alerts;
        }
        if($this->result->hasRedirection()){
            $array = array_merge($array, $this->result->getRedirection()->getAjaxParams($this->createRedirectionInput()));
        }
        return json_encode($array);
    }
    
    /**
     * @return \muuska\html\config\DefaultHtmlGlobalConfig
     */
    protected function createHtmlGlobalConfig()
    {
        return App::htmls()->createDefaultHtmlGlobalConfig($this->input->getLang(), $this->result->getAssetSetter(), $this->input->getTheme());
    }
    
    /**
     * @return \muuska\http\redirection\RedirectionInput
     */
    protected function createRedirectionInput(){
        if($this->urlCreator === null){
            $this->urlCreator = App::urls()->createDefaultControllerUrl($this->input);
        }
        return App::https()->createRedirectionInput($this->input->getResponse(), $this->urlCreator, $this->input->getVisitorInfoRecorder(), true, $this->getDataUsedOncePrefix().'alert_');
    }
    
    /**
     * @param string $relativePath
     * @param \muuska\translation\TemplateTranslator $translator
     * @param string $innerPath
     * @return \muuska\renderer\template\Template
     */
    protected function getThemeTemplate($relativePath, \muuska\translation\TemplateTranslator $translator = null, $innerPath = null) {
        $theme = $this->input->getTheme();
        $result = null;
        if($theme !== null){
            $result = $theme->createTemplate($relativePath, $translator, $innerPath);
        }
        return $result;
    }
    
    /**
     * @param string $assetType
     * @param string $location
     * @param string $library
     * @param int $priority
     * @param string $locationInPage
     * @return \muuska\asset\RelativeUriAsset
     */
    protected function addThemeAsset($assetType, $location, $library = null, $priority = null, $locationInPage = null) {
        $theme = $this->input->getTheme();
        $result = null;
        if($theme !== null){
            $result = $theme->createAsset($assetType, $location, $library, $priority, $locationInPage);
            $this->result->getAssetSetter()->addAsset($result);
        }
        return $result;
    }
    
    /**
     * @param string $string
     * @param string $context
     * @return string
     */
    protected function l($string, $context = '') {
        return ($this->translator !== null) ? $this->translator->translate($this->input->getLang(), $string, $context) : $string;
    }
    
    protected function processUpload() {
        $uploadHelper = App::helpers()->createUploadHelper($this->input);
        $result = $uploadHelper->processUploadFile();
        $this->result->addErrors($uploadHelper->getErrors());
        $this->result->addExtraFromArray($result);
    }
    
    protected function processDeleteUpload() {
        $uploadHelper = App::helpers()->createUploadHelper($this->input);
        $uploadHelper->processDeleteFile();
        $this->result->addErrors($uploadHelper->getErrors());
        $this->result->addErrors($uploadHelper->getErrors());
    }
}