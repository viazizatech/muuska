<?php
namespace muuska\project;

use muuska\controller\event\ControllerEventListener;
use muuska\url\event\UrlCreationEventListener;
use muuska\util\ExtraDataProvider;

interface SubProject extends ControllerEventListener, UrlCreationEventListener, ExtraDataProvider
{
    /**
	 * @param string $themeName
	 * @return bool
	 */
	public function isThemeImplemented($themeName);
	
	/**
	 * @param string $relativePath
	 * @param \muuska\translation\TemplateTranslator $translator
	 * @param string $innerPath
	 * @param bool $relatedToTheme
	 * @return \muuska\renderer\template\Template
	 */
	public function createTemplate($relativePath, \muuska\translation\TemplateTranslator $translator = null, $innerPath = null, $relatedToTheme = null);
	
	/**
	 * @return string
	 */
	public function getActiveThemeName();
    
    /**
	 * @param \muuska\controller\ControllerInput $input
	 * @return \muuska\controller\Controller
	 */
	public function createController(\muuska\controller\ControllerInput $input);
	
	/**
	 * @param bool $relatedToTheme
	 * @return \muuska\asset\RelativeAssetResolver
	 */
	public function getAssetResolver($relatedToTheme = true);
	
	/**
	 * @param string $name
	 * @param \muuska\asset\AssetSetter $assetSetter
	 * @return \muuska\asset\AssetGroup
	 */
	public function createAssetGroup($name, \muuska\asset\AssetSetter $assetSetter);
	
	/**
	 * @param string $assetType
	 * @param string $location
	 * @param string $library
	 * @param int $priority
	 * @param string $locationInPage
	 * @param bool $relatedToTheme
	 * @return \muuska\asset\RelativeUriAsset
	 */
	public function createAsset($assetType, $location, $library = null, $priority = null, $locationInPage = null, $relatedToTheme = null);
	
	/**
	 * @param string $location
	 * @param string $alt
	 * @param string $title
	 * @param string $library
	 * @param bool $relatedToTheme
	 * @return \muuska\html\RelativeHtmlImage
	 */
	public function createHtmlImage($location, $alt = null, $title = null, $library = null, $relatedToTheme = null);
	
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
	 * @param int $locationInPage
	 * @param bool $relatedToTheme
	 * @return \muuska\asset\AssetTranslation
	 */
	public function createJSTranslation($lang, $location, \muuska\asset\AssetSetter $assetSetter = null, $defaultScopeEnabled = true, $priority = null, $locationInPage = null, $relatedToTheme = null);
	
	/**
	 * @param \muuska\asset\AssetSetter $assetSetter
	 * @param \muuska\asset\AssetTranslation $assetTranslation
	 * @param array $innerScopes
	 */
	public function formatAssetTranslation(\muuska\asset\AssetSetter $assetSetter, \muuska\asset\AssetTranslation $assetTranslation, $innerScopes = array());

	/**
	 * @return bool
	 */
	public function isRelatedToTheme();
}
