<?php
namespace muuska\project;

interface Application extends Project
{
    public function run();
    
    /**
     * @param string $subAppName
     * @return \muuska\project\SubApplication
     */
    public function getSubApplication($subAppName);
    
    /**
     * @param string $subAppName
     * @return bool
     */
    public function hasSubApplication($subAppName);
    
	/**
	 * @param string $projectType
	 * @param string $projectName
	 * @return \muuska\project\Project
	 */
    public function getProject($projectType, $projectName);
	
	/**
	 * @return Project
	 */
	public function getFrameworkInstance();
	
	/**
	 * @param string $name
	 * @return Project
	 */
	public function getModuleInstance($name);
	
	/**
	 * @param string $projectType
	 * @param string $projectName
	 * @return \muuska\project\ProjectInfo
	 */
	public function getInstalledProjectInfo($projectType, $projectName);
	
	/**
	 * @param string $projectType
	 * @param string $projectName
	 * @return bool
	 */
	public function isProjectInstalled($projectType, $projectName);
	
	/**
	 * @param string $projectType
	 * @param string $projectName
	 * @return bool
	 */
	public function isProjectActive($projectType, $projectName);
	
	/**
	 * @return bool
	 */
	public function isAppInstalled();
	
	/**
	 * @return string
	 */
	public function getBaseUrl();
	
	/**
	 * @param \muuska\url\UrlCreationInput $input
	 * @return string
	 */
	public function createUrl(\muuska\url\UrlCreationInput $input);
	
	/**
	 * @return string
	 */
	public function getPublicDir();
	
	/**
	 * @return string
	 */
	public function getPublicUrl();
	
	/**
	 * @return string
	 */
	public function getUploadTmpDir();
	
	/**
	 * @param string $fileName
	 * @return string
	 */
	public function getUploadTmpFullFile($fileName);
	
	/**
	 * @return string
	 */
	public function getUploadTmpUrl();
	
	/**
	 * @param string $fileName
	 * @return string
	 */
	public function getUploadTmpFullUrl($fileName);
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param object $model
	 * @param string $field
	 * @return string
	 */
	public function getModelFileUrl(\muuska\model\ModelDefinition $modelDefinition, object $model, $field);
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param object $model
	 * @param string $field
	 * @return string
	 */
	public function getModelFullFile(\muuska\model\ModelDefinition $modelDefinition, object $model, $field);
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param string $field
	 * @return string
	 */
	public function getModelFileDir(\muuska\model\ModelDefinition $modelDefinition, $field);
	
	/**
	 * @param string $subAppName
	 * @param string $fileUrl
	 * @param string $fileLocation
	 * @param string $shortFileName
	 * @param bool $useOriginalFileDetails
	 * @param \muuska\util\upload\UploadInfo $uploadInfo
	 * @return string
	 */
	public function getFilePreview($subAppName, $fileUrl, $fileLocation, $shortFileName, $useOriginalFileDetails = false, \muuska\util\upload\UploadInfo $uploadInfo = null);
	
	/**
	 * @param bool $onlyActive
	 * @return \muuska\localization\LanguageInfo[]
	 */
	public function getLanguages($onlyActive = true);
	
	/**
	 * @param string $lang
	 * @return \muuska\localization\LanguageInfo
	 */
	public function getLanguageInfo($lang);
	
	/**
	 * @param string $name
	 * @param \muuska\config\Configuration $source
	 */
	public function registerConfiguration($name, \muuska\config\Configuration $configuration);
	
	/**
	 * @param string $name
	 * @return \muuska\config\Configuration
	 */
	public function getConfiguration($name);
	
    /**
     * @return \muuska\config\Configuration
     */
    public function getMainConfiguration();
    
    /**
	 * @param string $name
	 * @return bool
	 */
	public function hasConfiguration($name);
	
	/**
	 * @return string
	 */
	public function getDefaultLang();
	
	/**
	 * @return bool
	 */
	public function isDevMode();
	
	/**
	 * @param Project $project
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param \muuska\dao\DAOFactory $daoFactory
	 * @param \muuska\dao\DAOSource $daoSource
	 * @return \muuska\dao\DAO
	 */
	public function createAppModelDAO(Project $project, \muuska\model\ModelDefinition $modelDefinition, \muuska\dao\DAOFactory $daoFactory, \muuska\dao\DAOSource $daoSource);
	
	/**
	 * @param \muuska\dao\DAOSource $source
	 */
	public function registerDAOSource(\muuska\dao\DAOSource $source);
	
	/**
	 * @param string $code
	 * @return Project[]
	 */
	public function getProjectsForEvent($code);
	
	/**
	 * @return \muuska\util\event\EventTrigger
	 */
	public function getEventTrigger();
	
	/**
	 * @param string id
	 * @return \muuska\http\session\Session
	 */
	public function createDefaultSession($id = null);
	
	/**
	 * @return string
	 */
	public function getRootDir();
	
	/**
	 * @return string
	 */
	public function getLibrariesDir();
	
	/**
	 * @return string
	 */
	public function getCacheDir();
	
	/**
	 * @return string
	 */
	public function getStorageDir();
	
	/**
	 * @return string
	 */
	public function getRootConfigDir();
	
	/**
	 * @param string $name
	 * @param string $projectType
	 * @param string $projectName
	 * @return string
	 */
	public function getControllerFullName($name, $projectType, $projectName);
	
	/**
	 * @param string $name
	 * @param string $projectType
	 * @param string $projectName
	 * @return string
	 */
	public function getModelFullName($name, $projectType, $projectName);
	
	/**
	 * @return \muuska\mail\MailSender
	 */
	public function getDefaultMailSender();
	
	/**
	 * @param string $baseNamespace
	 * @param string $relativePath
	 */
	public function autoloadLibrary($baseNamespace, $relativePath);
	
	/**
	 * @return string
	 */
	public function getDefaultJsScope();
	
	/**
	 * @return string
	 */
	public function getTranslationDefaultJsScope();
	
	/**
	 * @param string $subAppName
	 * @return \muuska\config\Configuration
	 */
	public function getSubApplicationConfig($subAppName);
	
	/**
	 * @return string[]
	 */
	public function getEnabledSubApplications();
	
	/**
	 * @return \muuska\cache\CacheManager
	 */
	public function getCacheManager();
	
	/**
	 * @param \muuska\project\setup\ProjectSetupManager $projectSetupManager
	 * @param string $expectedProjectType
	 */
	public function initSetupManager(\muuska\project\setup\ProjectSetupManager $projectSetupManager, $expectedProjectType = null);
	
	/**
	 * @param Project $project
	 * @param \muuska\project\setup\ProjectUpgrade $projectUpgrade
	 * @return bool
	 */
	public function upgradeProject(Project $project, \muuska\project\setup\ProjectUpgrade $projectUpgrade);
}
