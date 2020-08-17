<?php
namespace muuska\project;

use muuska\asset\constants\AssetNames;
use muuska\constants\ActionCode;
use muuska\constants\FolderPath;
use muuska\constants\Names;
use muuska\http\constants\RequestType;
use muuska\project\constants\ProjectType;
use muuska\util\App;

class AbstractSubApplication extends AbstractSubProject implements SubApplication
{
    private $themeInstances = array();
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\SubApplication::runController()
     */
    public function runController(\muuska\dao\DAOFactory $daoFactory, \muuska\http\event\RequestParsingEvent $requestEvent, $outputEnabled = true){
        $visitorInfoRecorder = $this->createVisitorInfoRecorder($daoFactory, $requestEvent);
        $currentUser = $this->createCurrentUser($daoFactory, $requestEvent, $visitorInfoRecorder);
        $subAppConfig = $this->getConfig();
        if(!$requestEvent->hasAction()){
            $requestEvent->setAction(ActionCode::DEFAULT_PROCESS);
        }
        $result = null;
        $controller = $this->getAppControllerInstance($daoFactory, $requestEvent, $visitorInfoRecorder, $currentUser, $outputEnabled);
        if($controller === null){
            $controller = $this->getPageNotFoundController($daoFactory, $requestEvent, $visitorInfoRecorder, $currentUser, $outputEnabled);
        }
        if($controller !== null){
            if($currentUser->isLogged() ||
                (!$subAppConfig->getBool('authentification_required') && !$controller->isAuthentificationAlwaysRequired()) ||
                ($requestEvent->getController() === Names::LOGIN_CONTROLLER))
            {
                if ($controller->checkSecurityAccess()) {
                    $accessOk = true;
                    if(($requestEvent->getController() !== Names::LOGIN_CONTROLLER) && ($subAppConfig->getBool('access_checking_required') || $controller->isAccessCheckingAlwaysRequired())){
                        $accessOk = $currentUser->checkAccess($controller->getInput()->getActionResourceTree());
                    }
                    if ($accessOk) {
                        $result = $controller->executeAction();
                    } else {
                        $result = $controller->onAccessFailed();
                    }
                } else {
                    $result = $controller->onSecurityFailed();
                }
            }else{
                $result = $controller->onUserNotLogged();
            }
        }else{
            $requestEvent->getResponse()->sendError(501, 'Controller not found');
        }
        return $result;
    }
    
    /**
     * @param \muuska\dao\DAOFactory $daoFactory
     * @param \muuska\http\event\RequestParsingEvent $requestEvent
     * @param \muuska\http\VisitorInfoRecorder $visitorInfoRecorder
     * @param \muuska\security\CurrentUser $currentUser
     * @param bool $outputEnabled
     * @return \muuska\controller\Controller
     */
    protected function getAppControllerInstance(\muuska\dao\DAOFactory $daoFactory, \muuska\http\event\RequestParsingEvent $requestEvent, \muuska\http\VisitorInfoRecorder $visitorInfoRecorder, \muuska\security\CurrentUser $currentUser, $outputEnabled)
    {
        $controllerInstance = null;
        $projectType = $requestEvent->getProjectType();
        $project = null;
        if(empty($projectType) || ($projectType === ProjectType::APPLICATION)){
            $controllerInstance = $this->createControllerFromProject(App::getApp(), $daoFactory, $requestEvent, $visitorInfoRecorder, $currentUser, $outputEnabled);
            if($controllerInstance === null){
                $controllerInstance = $this->createControllerFromProject(App::getFramework(), $daoFactory, $requestEvent, $visitorInfoRecorder, $currentUser, $outputEnabled);
            }
        }else{
            $project = App::getApp()->getProject($projectType, $requestEvent->getProjectName());
            if($project !== null){
                $controllerInstance = $this->createControllerFromProject($project, $daoFactory, $requestEvent, $visitorInfoRecorder, $currentUser, $outputEnabled);
            }
        }
        return $controllerInstance;
    }
    
    /**
     * @param \muuska\dao\DAOFactory $daoFactory
     * @param \muuska\http\event\RequestParsingEvent $requestEvent
     * @param \muuska\http\VisitorInfoRecorder $visitorInfoRecorder
     * @param \muuska\security\CurrentUser $currentUser
     * @param bool $outputEnabled
     * @return \muuska\controller\Controller
     */
    protected function getPageNotFoundController(\muuska\dao\DAOFactory $daoFactory, \muuska\http\event\RequestParsingEvent $requestEvent, \muuska\http\VisitorInfoRecorder $visitorInfoRecorder, \muuska\security\CurrentUser $currentUser, $outputEnabled)
    {
        return null;
    }
    
    /**
     * @param \muuska\project\Project $project
     * @param \muuska\dao\DAOFactory $daoFactory
     * @param \muuska\http\event\RequestParsingEvent $requestEvent
     * @param \muuska\http\VisitorInfoRecorder $visitorInfoRecorder
     * @param \muuska\security\CurrentUser $currentUser
     * @param bool $outputEnabled
     * @return \muuska\controller\Controller
     */
    protected function createControllerFromProject(\muuska\project\Project $project, \muuska\dao\DAOFactory $daoFactory, \muuska\http\event\RequestParsingEvent $requestEvent, \muuska\http\VisitorInfoRecorder $visitorInfoRecorder, \muuska\security\CurrentUser $currentUser, $outputEnabled){
        $subAppName = $requestEvent->getSubAppName();
        return $project->hasSubProject($subAppName) ? $project->getSubProject($subAppName)->createController($this->createControllerInput($project, $daoFactory, $requestEvent, $visitorInfoRecorder, $currentUser, $outputEnabled)) : null;
    }
    
    /**
     * @param \muuska\project\Project $project
     * @param \muuska\dao\DAOFactory $daoFactory
     * @param \muuska\http\event\RequestParsingEvent $requestEvent
     * @param \muuska\http\VisitorInfoRecorder $visitorInfoRecorder
     * @param \muuska\security\CurrentUser $currentUser
     * @param bool $outputEnabled
     * @return \muuska\controller\ControllerInput
     */
    protected function createControllerInput(\muuska\project\Project $project, \muuska\dao\DAOFactory $daoFactory, \muuska\http\event\RequestParsingEvent $requestEvent, \muuska\http\VisitorInfoRecorder $visitorInfoRecorder, \muuska\security\CurrentUser $currentUser, $outputEnabled)
    {
        $request = $requestEvent->getRequest();
        $name = $requestEvent->getController();
        $fullName = App::getApp()->getControllerFullName($name, $project->getType(), $project->getName());
        $requestType = ($request->hasQueryParam('ajax') || $request->hasPostParam('ajax')) ? RequestType::AJAX : RequestType::SIMPLE;
        $lang = $requestEvent->getLang();
        if(empty($lang) && $currentUser->hasPreferredLang()){
            $lang = $currentUser->getPreferredLang();
        }
        if(empty($lang)){
            $lang = App::getApp()->getDefaultLang();
        }
        return App::controllers()->createControllerInput($project, $requestEvent->getSubAppName(), $lang, $request, $requestEvent->getResponse(), $requestEvent->getAction(), $requestEvent->getPathParams(), $currentUser, $visitorInfoRecorder, $daoFactory, $name, $fullName, $requestType, $outputEnabled, $requestEvent->getVariationTriggers());
    }
    
    /**
     * @param \muuska\dao\DAOFactory $daoFactory
     * @param \muuska\http\event\RequestParsingEvent $requestEvent
     * @return \muuska\http\VisitorInfoRecorder
     */
    protected function createVisitorInfoRecorder(\muuska\dao\DAOFactory $daoFactory, \muuska\http\event\RequestParsingEvent $requestEvent)
    {
        $mainConfig = App::getApp()->getMainConfiguration();
        $subAppName = $requestEvent->getSubAppName();
        $cookieLifetime = $this->getConfig()->getInt('cookie_lifetime', 480);
        $cookieName = 'Part_'.$subAppName;
        if ($cookieLifetime > 0) {
            $cookieLifetime = time() + (max($cookieLifetime, 1) * 3600);
        }
        return App::https()->createCookieVisitorInfoRecorder($requestEvent->getRequest(), $requestEvent->getResponse(), $cookieName, '', $cookieLifetime, $mainConfig->getString('cipher_algorithm'));
    }
    
    /**
     * @param \muuska\dao\DAOFactory $daoFactory
     * @param \muuska\http\event\RequestParsingEvent $requestEvent
     * @param \muuska\http\VisitorInfoRecorder $visitorInfoRecorder
     * @return \muuska\security\CurrentUser
     */
    protected function createCurrentUser(\muuska\dao\DAOFactory $daoFactory, \muuska\http\event\RequestParsingEvent $requestEvent, \muuska\http\VisitorInfoRecorder $visitorInfoRecorder)
    {
        return App::securities()->createDefaultCurrentUser($daoFactory, $requestEvent->getSubAppName(), $requestEvent->getRequest(), $requestEvent->getResponse(), $visitorInfoRecorder, $this->getPersonInfoResolver($daoFactory, $requestEvent, $visitorInfoRecorder));
    }
    
    /**
     * @param \muuska\dao\DAOFactory $daoFactory
     * @param \muuska\http\event\RequestParsingEvent $requestEvent
     * @param \muuska\http\VisitorInfoRecorder $visitorInfoRecorder
     * @return \muuska\security\PersonInfoResolver
     */
    protected function getPersonInfoResolver(\muuska\dao\DAOFactory $daoFactory, \muuska\http\event\RequestParsingEvent $requestEvent, \muuska\http\VisitorInfoRecorder $visitorInfoRecorder)
    {
        return null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\SubApplication::getConfiguredThemeName()
     */
    public function getConfiguredThemeName()
    {
        return $this->getConfig()->getString('theme_name', Names::DEFAULT_THEME);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\SubApplication::getConfig()
     */
    public function getConfig()
    {
        return App::getApp()->getSubApplicationConfig($this->subAppName);
    }

    /**
     * {@inheritDoc}
     * @see \muuska\project\SubApplication::getThemeByName()
     */
    public function getThemeByName($name)
    {
        if(!isset($this->themeInstances[$name])){
            $this->themeInstances[$name] = $this->createThemeInstance($name);
        }
        return $this->themeInstances[$name];
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\SubApplication::getActiveTheme()
     */
    public function getActiveTheme()
    {
        return $this->getThemeByName($this->getConfiguredThemeName());
    }
    
    /**
     * @param string $name
     * @return \muuska\util\theme\DefaultTheme
     */
    protected function createThemeInstance($name) {
        $result = null;
        if($name === Names::DEFAULT_THEME){
            $result = App::utils()->createDefaultTheme($name, App::getFramework()->getCorePath() . '/theme', $this->createThemeConfig($name));
        }
        return $result;
    }
    
    /**
     * @param string $name
     * @return \muuska\util\theme\DefaultTheme
     */
    protected function createThemeConfig($name) {
        $file = App::getApp()->getRootConfigDir() . FolderPath::ALL_THEMES . '/'.$name . '/'.strtolower($this->subAppName).'.json';
        return App::configs()->createJSONConfiguration($file);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\AbstractSubProject::createAssetGroup()
     */
    public function createAssetGroup($name, \muuska\asset\AssetSetter $assetSetter){
        $result = parent::createAssetGroup($name, $assetSetter);
        if(($result === null) && ($name === AssetNames::SUB_APP_CUSTOM_GROUP)){
            $result = $this->createCustomAssetGroup($name, $assetSetter);
        }
        if(($result !== null) && !$assetSetter->hasAssetGroup($name)){
            $assetSetter->addAssetGroup($result);
        }
        return $result;
    }
    
    /**
     * @param string $name
     * @param \muuska\asset\AssetSetter $assetSetter
     * @return \muuska\asset\AssetGroup
     */
    public function createCustomAssetGroup($name, \muuska\asset\AssetSetter $assetSetter){
        return null;
    }
}
