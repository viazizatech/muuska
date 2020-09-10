<?php
namespace muuska\util\theme;

interface Theme
{
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
	 * @param \muuska\controller\event\ControllerPageFormatingEvent $event
	 */
    public function formatControllerPage(\muuska\controller\event\ControllerPageFormatingEvent $event);
    
    /**
     * @param \muuska\mail\event\MailPageFormatingEvent $event
     */
    public function formatMailPage(\muuska\mail\event\MailPageFormatingEvent $event);
    
    /**
     * @param string $fileUrl
     * @param string $fileLocation
     * @param string $shortFileName
     * @param bool $useOriginalFileDetails
     * @param \muuska\util\upload\UploadInfo $uploadInfo
     * @return string
     */
    public function getFilePreview($fileUrl, $fileLocation, $shortFileName, $useOriginalFileDetails = false, \muuska\util\upload\UploadInfo $uploadInfo = null);
    
    /**
     * @param string $componentName
     * @return \muuska\renderer\HtmlContentRenderer
     */
    public function getHtmlRenderer($componentName);
    
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
    public function getCoreDir();
    
    /**
     * @param string $style
     * @param string $secondStyle
     * @return string[]
     */
    public function getCommandClasses($style, $secondStyle = null);
    
    /**
     * @param string $class
     * @return string
     */
    public function getFinalCommandStyleClass($class);
    
    /**
     * @param string $class
     * @return string
     */
    public function getWidthClass($class);
    
    /**
     * @param string $class
     * @return string
     */
    public function getFinalAlertStyleClass($class);
    
    /**
     * @param string $type
     * @param string $style
     * @return string[]
     */
    public function getAlertClassesFromType($type, $style = null);

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
	 * @return string
	 */
	public function getSubPathInApp();
}
