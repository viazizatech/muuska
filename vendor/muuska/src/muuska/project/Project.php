<?php
namespace muuska\project;

use muuska\dao\event\DAOEventListener;
use muuska\util\ExtraDataProvider;

interface Project extends DaoEventListener, ExtraDataProvider
{
    /**
     * @return string
     */
    public function getName();
    
    /**
     * @return string
     */
    public function getType();
    
    /**
     * @return string
     */
    public function getVersion();
    
    /**
     * @param string $subAppName
     * @return \muuska\project\SubProject
     */
    public function getSubProject($subAppName);
    
    /**
     * @param string $subAppName
     * @return bool
     */
    public function hasSubProject($subAppName);
	
    /**
	 * @return \muuska\project\ProjectInfo
	 */
	public function getInstalledInfo();
	
	/**
	 * @return bool
	 */
	public function isInstalled();
	
	/**
	 * @return bool
	 */
	public function isActive();
	
	/**
	 * @return bool
	 */
	public function isUpToDate();
	
    /**
	 * @param string $relativePath
	 * @param \muuska\translation\TemplateTranslator $translator
	 * @param string $innerPath
	 * @return \muuska\renderer\template\Template
	 */
	public function createTemplate($relativePath, \muuska\translation\TemplateTranslator $translator = null, $innerPath = null);
	
	/**
	 * @return string
	 */
	public function getCorePath();
	
	/**
	 * @return string
	 */
	public function getCoreDir();
	
	/**
	 * @return string
	 */
	public function getConfigDir();
	
	/**
	 * @return string
	 */
	public function getTranslationDirPattern();
	
	/**
	 * @return string
	 */
	public function getTemplateDir();
	
	/**
	 * @return string
	 */
	public function getAssetPathPattern();
	
	/**
	 * @return string
	 */
	public function getSetupDir();
	
	/**
	 * @return bool
	 */
	public function hasSpecificDAOSource();
	
	/**
	 * @param \muuska\model\ModelDefinition $modelDefinition
	 * @param \muuska\dao\DAOFactory $daoFactory
	 * @param \muuska\dao\DAOSource $daoSource
	 * @return \muuska\dao\DAO
	 */
	public function createModelDAO(\muuska\model\ModelDefinition $modelDefinition, \muuska\dao\DAOFactory $daoFactory, \muuska\dao\DAOSource $daoSource);
	
	/**
	 * @param \muuska\dao\DAOFactory $daoFactory
	 * @return string
	 */
	public function getSpecificDAOSource(\muuska\dao\DAOFactory $daoFactory);
	
	/**
	 * @param string $name
	 * @param \muuska\asset\AssetSetter $assetSetter
	 * @return \muuska\asset\AssetGroup
	 */
	public function createAssetGroup($name, \muuska\asset\AssetSetter $assetSetter);
	
	/**
	 * @return \muuska\asset\RelativeAssetResolver
	 */
	public function getAssetResolver();
	
	/**
	 * @param string $assetType
	 * @param string $location
	 * @param string $library
	 * @param int $priority
	 * @param string $locationInPage
	 * @return \muuska\asset\RelativeUriAsset
	 */
	public function createAsset($assetType, $location, $library = null, $priority = null, $locationInPage = null);
	
	/**
	 * @param string $location
	 * @param string $alt
	 * @param string $title
	 * @param string $library
	 * @return \muuska\html\RelativeHtmlImage
	 */
	public function createHtmlImage($location, $alt = null, $title = null, $library = null);
    
	/**
	 * @param \muuska\translation\config\TranslatorConfig $translatorConfig
	 * @param \muuska\translation\Translator $alternativeTranslator
	 * @return \muuska\translation\Translator
	 */
	public function getTranslator(\muuska\translation\config\TranslatorConfig $translatorConfig, \muuska\translation\Translator $alternativeTranslator = null);
	
	/**
	 * @param string $lang
	 * @param string $location
	 * @param \muuska\asset\AssetSetter $assetSetter
	 * @param bool $defaultScopeEnabled
	 * @param int $priority
	 * @param string $locationInPage
	 * @return \muuska\asset\AssetTranslation
	 */
	public function createJSTranslation($lang, $location, \muuska\asset\AssetSetter $assetSetter = null, $defaultScopeEnabled = true, $priority = null, $locationInPage = null);
	
	/**
	 * @param \muuska\asset\AssetSetter $assetSetter
	 * @param \muuska\asset\AssetTranslation $assetTranslation
	 * @param array $innerScopes
	 */
	public function formatAssetTranslation(\muuska\asset\AssetSetter $assetSetter, \muuska\asset\AssetTranslation $assetTranslation, $innerScopes = array());
	
	/**
	 * @return array
	 */
	public function getTranslationJsScopes();
	
	/**
	 * @return string
	 */
	public function getSubPathInApp();
	
	/**
	 * @return string[]
	 */
	public function getSubFoldersInApp();
	
	/**
	 * @param string $subAppName
	 * @param \muuska\security\ResourceTree $subResourceTree
	 * @return \muuska\security\ResourceTree
	 */
	public function createResourceTree($subAppName, \muuska\security\ResourceTree $subResourceTree = null);
	
	/**
	 * @param \muuska\project\setup\ProjectUpgrade $projectUpgrade
	 * @return bool
	 */
	public function checkUpgradeInput(\muuska\project\setup\ProjectUpgrade $projectUpgrade);
}
