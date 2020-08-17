<?php
namespace muuska\project;

use muuska\asset\constants\AssetNames;
use muuska\constants\FolderPath;
use muuska\project\constants\ProjectType;
use muuska\util\App;

abstract class AbstractApplication extends AbstractProject implements Application
{
	/**
	 * @var string
	 */
	protected $rootDir;
	
	/**
	 * @var \muuska\http\Router
	 */
	protected $router;
	
	/**
	 * @var Project[]
	 */
	private static $projectsInstances = array();
	
	/**
	 * @var ProjectInfo[]
	 */
	private static $installedProjectsInfos;
	
	private $appTmpData;
	
	/**
	 * @var \muuska\dao\DAOFactory
	 */
	protected $daoFactory;
	
	const MAIN_CONFIG_SOURCE_NAME = 'main';
	
	/**
	 * @param string $rootDir
	 * @param string $corePath
	 * @param string $frameworkPath
	 */
	public function __construct($rootDir, $corePath, $frameworkPath)
    {
		App::init();
		if(!App::isMainApplicationInitialized()){
		    App::setMainApplication($this);
		}
		$this->type = ProjectType::APPLICATION;
		$this->rootDir = $rootDir;
		$this->corePath = $corePath;
		self::$projectsInstances[$this->type][''] = $this;
		$this->registerMainConfigurations();
		$this->appTmpData['subAppConfigs'] = $this->loadSubApplicationConfigs();
		$this->daoFactory = App::daos()->createDAOFactory($this->getMainConfiguration()->getString('dao_source', 'json'));
		$this->registerMainDAOSources();
		$this->loadInstalledProjectsInfos();
		self::$projectsInstances[ProjectType::FRAMEWORK][''] = App::projects()->createFramework(App::projects()->createProjectInput($frameworkPath, $this->daoFactory));
		$this->router = App::https()->createRouter($this->daoFactory);
		$this->onCreate();
		$this->getEventTrigger()->fireAppInitialization(App::utils()->createAppInitializationEvent($this));
    }
    
    public function run(){
        $this->router->run();
    }
    protected function loadSubApplicationConfigs(){
        $result = array();
        $config = App::configs()->createJSONConfiguration($this->getRootConfigDir(). 'sub_apps.json');
        $keys = $config->getKeys();
        foreach ($keys as $key) {
            $result[$key] = $config->getInnerConfiguration($key);
        }
        return $result;
    }
    
    protected function registerMainDAOSources(){
        $this->registerDaoSource(App::daos()->createJSONDAOSource('json'));
    }
    
    protected function registerMainConfigurations(){
        $this->registerConfiguration(self::MAIN_CONFIG_SOURCE_NAME, App::configs()->createJSONConfiguration($this->getRootConfigDir().'main.json'));
    }
    
    protected function loadInstalledProjectsInfos(){
        if(self::$installedProjectsInfos === null){
            self::$installedProjectsInfos = array();
            $projects = $this->daoFactory->getDAO(App::projects()->getProjectModelDefinition(), 'json')->getData();
            foreach ($projects as $project) {
                self::$installedProjectsInfos[$project->getType()][$project->getName()] = $project;
            }
        }
    }
    
    /**
     * @param string $projectType
     * @param string $projectName
     * @return Project
     */
    protected static function getLoadedProjectInstance($projectType, $projectName){
        $project = null;
        if(isset(self::$projectsInstances[$projectType]) && isset(self::$projectsInstances[$projectType][$projectName])){
            $project = self::$projectsInstances[$projectType][$projectName];
        }
        return $project;
    }
    
    /**
     * @param string $projectType
     * @param string $name
     * @return Project
     */
    protected function createProjectInstance($projectType, $projectName){
        $project = null;
        $object = $this->getInstalledProjectInfo($projectType, $projectName);
        if(($object !== null) && !empty($object->getId())){
            $class = $object->getMainClass();
            $this->autoloadProject($projectType, $projectName);
            $project = new $class(App::projects()->createProjectInput($this->getProjectCorePath($projectType, $projectName), $this->daoFactory));
        }
        return $project;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getSubApplication()
     */
    public function getSubApplication($subAppName){
        return $this->getSubProject($subAppName);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::hasSubApplication()
     */
    public function hasSubApplication($subAppName){
        return $this->hasSubProject($subAppName);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getProject()
     */
    public function getProject($projectType, $projectName){
        $project = null;
        if(isset(self::$projectsInstances[$projectType]) && isset(self::$projectsInstances[$projectType][$projectName])){
            $project = self::$projectsInstances[$projectType][$projectName];
        }else{
            $project = $this->createProjectInstance($projectType, $projectName);
            if($project !== null){
                self::$projectsInstances[$projectType][$projectName] = $project;
            }
        }
        return $project;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getFrameworkInstance()
     */
    public function getFrameworkInstance(){
        return self::getLoadedProjectInstance(ProjectType::FRAMEWORK, null);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getModuleInstance()
     */
    public function getModuleInstance($name){
        return $this->getProject(ProjectType::MODULE, $name);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getInstalledProjectInfo()
     */
    public function getInstalledProjectInfo($projectType, $projectName){
        $object = null;
        if(isset(self::$installedProjectsInfos[$projectType]) && isset(self::$installedProjectsInfos[$projectType][$projectName])){
            $object = self::$installedProjectsInfos[$projectType][$projectName];
        }
        return $object;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::isProjectInstalled()
     */
    public function isProjectInstalled($projectType, $projectName){
        $object = $this->getInstalledProjectInfo($projectType, $projectName);
        return (($object !== null) && !empty($object->getId()));
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::isProjectActive()
     */
    public function isProjectActive($projectType, $projectName){
        $object = $this->getInstalledProjectInfo($projectType, $projectName);
        return (($object !== null) && $object->isActive());
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::isAppInstalled()
     */
    public function isAppInstalled(){
        return ($this->getMainConfiguration()->getBool('app_installed', false) && $this->isInstalled());
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getBaseUrl()
     */
    public function getBaseUrl(){
        if(!isset($this->appTmpData['baseUrl'])){
            $mainConfig = $this->getMainConfiguration();
            $this->appTmpData['baseUrl'] = ($mainConfig->getBool('ssl_enabled') ? 'https://': 'http://') . $mainConfig->getString('server_host') . $mainConfig->getString('context_path').'/';
        }
        return $this->appTmpData['baseUrl'];
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::createUrl()
     */
    public function createUrl(\muuska\url\UrlCreationInput $input){
        $event = App::urls()->createUrlCreationEvent($this, $input);
        return App::getEventTrigger()->fireUrlCreation($event) ? $this->router->createUrl($input) : $event->getUrl();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getPublicDir()
     */
    public function getPublicDir()
    {
        return $this->rootDir . FolderPath::PUBLIC.'/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getPublicUrl()
     */
    public function getPublicUrl()
    {
        return $this->getBaseUrl().FolderPath::PUBLIC.'/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getUploadTmpDir()
     */
    public function getUploadTmpDir(){
        return $this->getPublicDir().FolderPath::UPLOAD_TMP.'/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getUploadTmpFullFile()
     */
    public function getUploadTmpFullFile($fileName){
        return $this->getUploadTmpDir().$fileName;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getUploadTmpUrl()
     */
    public function getUploadTmpUrl(){
        return $this->getPublicUrl().FolderPath::UPLOAD_TMP.'/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getUploadTmpFullUrl()
     */
    public function getUploadTmpFullUrl($fileName){
        return $this->getUploadTmpUrl().$fileName;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getModelFileUrl()
     */
    public function getModelFileUrl(\muuska\model\ModelDefinition $modelDefinition, object $model, $field){
        return $this->getPublicUrl().$modelDefinition->getFileFullPath($model, $field);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getModelFullFile()
     */
    public function getModelFullFile(\muuska\model\ModelDefinition $modelDefinition, object $model, $field){
        return $this->getPublicDir().$modelDefinition->getFileFullPath($model, $field);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getModelFileDir()
     */
    public function getModelFileDir(\muuska\model\ModelDefinition $modelDefinition, $field){
        return $this->getPublicDir().$modelDefinition->getFilePath($field);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getFilePreview()
     */
    public function getFilePreview($subAppName, $fileUrl, $fileLocation, $shortFileName, $useOriginalFileDetails = false, \muuska\util\upload\UploadInfo $uploadInfo = null){
        $result = '';
        $directlyPreviewableExtensions = array('jpeg', 'jpg', 'png', 'gif', 'svg');
        $extension = App::getFileTools()->getFileExtension($fileLocation);
        if(in_array($extension, $directlyPreviewableExtensions)){
            $result = '<img src="'.$fileUrl.'" alt="'.$shortFileName.'">';
        }else{
            $theme = $this->hasSubApplication($subAppName) ? $this->getSubApplication($subAppName)->getActiveTheme() : null;
            if($theme !== null){
                $result = $theme->getFilePreview($fileUrl, $fileLocation, $shortFileName, $useOriginalFileDetails, $uploadInfo);
            }else{
                $result = $shortFileName;
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getLanguages()
     */
    public function getLanguages($onlyActive = true){
        if (!isset($this->appTmpData['languages'])) {
            $dao = $this->daoFactory->getDAO(App::localizations()->getLanguageModelDefinitionInstance(), 'json');
            $seletionConfig = $dao->createSelectionConfig();
            $seletionConfig->setAllLangsEnabled(true);
            $this->appTmpData['languages'] = $dao->getData($seletionConfig);
        }
        
        $languages = array();
        foreach ($this->appTmpData['languages'] as $language) {
            if (!$onlyActive || $language->isActive()) {
                $languages[$language->getUniqueCode()] = $language;
            }
        }
        return $languages;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getLanguageInfo()
     */
    public function getLanguageInfo($lang){
        $languages = $this->getLanguages(false);
        return isset($languages[$lang]) ? $languages[$lang] : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::registerConfiguration()
     */
    public function registerConfiguration($name, \muuska\config\Configuration $configuration){
        if(!isset($this->appTmpData['configs'])||!isset($this->appTmpData['configs'][$name])){
            $this->appTmpData['configs'][$name] = $configuration;
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getConfiguration()
     */
    public function getConfiguration($name){
        return $this->hasConfiguration($name) ? $this->appTmpData['configs'][$name] : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getMainConfiguration()
     */
    public function getMainConfiguration(){
        return $this->getConfiguration(self::MAIN_CONFIG_SOURCE_NAME);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::hasConfiguration()
     */
    public function hasConfiguration($name){
        return (isset($this->appTmpData['configs']) && isset($this->appTmpData['configs'][$name]));
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getDefaultLang()
     */
    public function getDefaultLang(){
        return $this->getMainConfiguration()->getString('default_lang', 'en');
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::isDevMode()
     */
    public function isDevMode(){
        return $this->getMainConfiguration()->getBool('dev_mode', true);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::createAppModelDAO()
     */
    public function createAppModelDAO(Project $project, \muuska\model\ModelDefinition $modelDefinition, \muuska\dao\DAOFactory $daoFactory, \muuska\dao\DAOSource $daoSource) {
        return $project->createModelDAO($modelDefinition, $daoFactory, $daoSource);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::registerDAOSource()
     */
    public function registerDAOSource(\muuska\dao\DAOSource $source){
        $this->daoFactory->registerSource($source);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getProjectsForEvent()
     */
    public function getProjectsForEvent($code){
        $result = array();
        foreach (self::$installedProjectsInfos as $projectInfos) {
            foreach ($projectInfos as $projectInfo) {
                if($projectInfo->isRegisterAtEvent($code)){
                    $result[] = $this->getProject($projectInfo->getType(), $projectInfo->getName());
                }
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getEventTrigger()
     */
    public function getEventTrigger(){
        if(!isset($this->appTmpData['eventTrigger'])){
            $this->appTmpData['eventTrigger'] = App::utils()->createDefaultEventTrigger();
        }
        return $this->appTmpData['eventTrigger'];
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::createDefaultSession()
     */
    public function createDefaultSession($id = null){
        return App::https()->createNativeSession($id);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getRootDir()
     */
    public function getRootDir(){
        return $this->rootDir;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getLibrariesDir()
     */
    public function getLibrariesDir(){
        return $this->rootDir . FolderPath::LIBRARIES . '/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getStorageDir()
     */
    public function getStorageDir(){
        return $this->rootDir . FolderPath::STORAGE . '/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getCacheDir()
     */
    public function getCacheDir(){
        return $this->getStorageDir() . FolderPath::CACHE . '/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getRootConfigDir()
     */
    public function getRootConfigDir(){
        return $this->getStorageDir() . FolderPath::CONFIG . '/';
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getControllerFullName()
     */
    public function getControllerFullName($name, $projectType, $projectName){
        return strtolower($projectType) . (empty($projectName) ? '' : '-'.$projectName) . '-' . $name;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getDAOFullName()
     */
    public function getModelFullName($name, $projectType, $projectName){
        return strtolower($projectType) . (empty($projectName) ? '' : '-'.$projectName) . '-' . $name;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getDefaultMailSender()
     */
    public function getDefaultMailSender(){
        if(!isset($this->appTmpData['mailSender'])){
            $this->appTmpData['mailSender'] = App::mails()->createPHPMailerSender('smtp', App::configs()->createJSONConfiguration($this->getRootConfigDir().'mail/main.json'));
        }
        return $this->appTmpData['mailSender'];
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::autoloadLibrary()
     */
    public function autoloadLibrary($baseNamespace, $relativePath){
        \muuska\util\DefaultAutoloader::registerNew($baseNamespace, $this->rootDir . $relativePath);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getDefaultJsScope()
     */
    public function getDefaultJsScope(){
        return AssetNames::DEFAULT_VAR_SCOPE;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getTranslationDefaultJsScope()
     */
    public function getTranslationDefaultJsScope(){
        return AssetNames::DEFAULT_TRANSLATION_SCOPE;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getSubApplicationConfig()
     */
    public function getSubApplicationConfig($subAppName){
        return (isset($this->appTmpData['subAppConfigs']) && isset($this->appTmpData['subAppConfigs'][$subAppName])) ? $this->appTmpData['subAppConfigs'][$subAppName] : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getEnabledSubApplications()
     */
    public function getEnabledSubApplications(){
        return isset($this->appTmpData['subAppConfigs']) ? array_keys($this->appTmpData['subAppConfigs']) : array();
    }
    
    /**
     * @param string $rootDir
     * @param string $frameworkPath
     * @param string $corePath
     */
    public static function start($rootDir, $frameworkPath = null, $corePath = null){
        if(empty($corePath)){
            $corePath = 'app';
        }
        if(empty($frameworkPath)){
            $frameworkPath = 'vendor/muuska';
        }
        $application = new static($rootDir, $corePath, $frameworkPath);
        $application->run();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::getCacheManager()
     */
    public function getCacheManager()
    {
        if(!isset($this->appTmpData['cacheManager'])){
            $this->appTmpData['cacheManager'] = App::caches()->createFileCacheManager();;
        }
        return $this->appTmpData['cacheManager'];
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::initSetupManager()
     */
    public function initSetupManager(\muuska\project\setup\ProjectSetupManager $projectSetupManager, $expectedProjectType = null){
        $projectSetupManager->setProjectManager($this->getProjectManager());
        if(empty($expectedProjectType) || ($expectedProjectType === ProjectType::FRAMEWORK)){
            $projectSetupManager->addSetup(App::projects()->createFrameworkSetup($this->getFrameworkInstance()));
        }
        if(empty($expectedProjectType) || ($expectedProjectType === ProjectType::APPLICATION)){
            $setup = $this->createAppSetup();
            if($setup !== null){
                $projectSetupManager->addSetup($setup);
            }
        }
        $otherProjectTypes = array(ProjectType::MODULE/*, ProjectType::LIBRARY, ProjectType::CUSTOM*/);
        foreach ($otherProjectTypes as $projectType) {
            if(empty($expectedProjectType) || ($expectedProjectType === $projectType)){
                $setupClasses = App::getFileTools()->getArrayFromJsonFile($this->getStorageDir().'setup/'.strtolower($projectType).'.json');
                foreach ($setupClasses as $projectName => $setupClass) {
                    $setup = $this->createProjectSetup($projectType, $projectName, $setupClass);
                    if($setup !== null){
                        $projectSetupManager->addSetup($setup);
                    }
                }
            }
        }
        
    }
    
    /**
     * @return \muuska\project\setup\ProjectManager
     */
    protected function getProjectManager(){
        return App::projects()->createDefaultProjectManager($this->daoFactory);
    }
    
    /**
     * @return \muuska\project\setup\ProjectSetup
     */
    protected abstract function createAppSetup();
    
    /**
     * @param string $projectType
     * @param string $projectName
     * @param string $setupClass
     * @return \muuska\project\setup\ProjectSetup
     */
    protected function createProjectSetup($projectType, $projectName, $setupClass){
        $this->autoloadProject($projectType, $projectName);
        return new $setupClass(App::projects()->createProjectInput($this->getProjectCorePath($projectType, $projectName), $this->daoFactory));
    }
    
    /**
     * @param string $projectType
     * @param string $projectName
     */
    protected function autoloadProject($projectType, $projectName){
        require_once $this->rootDir . $this->getProjectCorePath($projectType, $projectName) . '/' . 'autoload.php';
    }
    
    /**
     * @param string $projectType
     * @param string $projectName
     * @return string
     */
    protected function getProjectCorePath($projectType, $projectName){
        return strtolower($projectType) . '/' . $projectName;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\Application::upgradeProject()
     */
    public function upgradeProject(Project $project, \muuska\project\setup\ProjectUpgrade $projectUpgrade){
        $result = false;
        $realProject = $this->getProject($project->getType(), $project->getName());
        if(($realProject === null) && ($project->getType() === ProjectType::FRAMEWORK)){
            $realProject = $project;
        }
        if(!$realProject->isUpToDate() && $realProject->checkUpgradeInput($projectUpgrade)){
            $model = $this->getInstalledProjectInfo($realProject->getType(), $realProject->getName());
            if(($model !== null) && $projectUpgrade->upgrade()){
                $model->setVersion($realProject->getVersion());
                if($projectUpgrade->isEventChanged()){
                    $model->setEvents($projectUpgrade->getEvents());
                }
                $model->setLastUpgradeDate(date('Y-m-d H:i:s'));
                $result = $this->daoFactory->getDAO(App::projects()->getProjectModelDefinition(), 'json')->update($model);
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\project\AbstractProject::createAssetGroup()
     */
    public function createAssetGroup($name, \muuska\asset\AssetSetter $assetSetter){
        $result = parent::createAssetGroup($name, $assetSetter);
        if(($result === null) && ($name === AssetNames::APP_CUSTOM_GROUP)){
            $result = $this->createCustomAssetGroup($name, $assetSetter);
        }elseif(($result === null) && ($name === AssetNames::APP_MAIL_CUSTOM_GROUP)){
            $result = $this->createMailAssetGroup($name, $assetSetter);
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
    
    /**
     * @param string $name
     * @param \muuska\asset\AssetSetter $assetSetter
     * @return \muuska\asset\AssetGroup
     */
    public function createMailAssetGroup($name, \muuska\asset\AssetSetter $assetSetter){
        return null;
    }
}
