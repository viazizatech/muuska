<?php
namespace muuska\instantiator;

class Assets
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Assets
	 */
	public static function getInstance(){
		if(self::$instance === null){
			self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @param string $assetType
     * @param string $location
     * @param int $priority
     * @param string $locationInPage
	 * @return \muuska\asset\AbsoluteUriAsset
	 */
	public function createAbsoluteUriAsset($assetType, $location, $priority = null, $locationInPage = null)
	{
	    return new \muuska\asset\AbsoluteUriAsset($assetType, $location, $priority, $locationInPage);
	}
	
	/**
	 * @param string $assetType
     * @param string $location
     * @param \muuska\asset\RelativeAssetResolver $resolver
     * @param string $library
     * @param int $priority
     * @param string $locationInPage
	 * @return \muuska\asset\RelativeUriAsset
	 */
	public function createRelativeUriAsset($assetType, $location, \muuska\asset\RelativeAssetResolver $resolver, $library = null, $priority = null, $locationInPage = null)
	{
	    return new \muuska\asset\RelativeUriAsset($assetType, $location, $resolver, $library, $priority, $locationInPage);
	}
	
	/**
	 * @param string $name
     * @param \muuska\asset\SingleAsset[] $singleAssets
	 * @return \muuska\asset\AssetGroup
	 */
	public function createAssetGroup($name, $singleAssets = array())
	{
	    return new \muuska\asset\AssetGroup($name, $singleAssets);
	}
	
	/**
	 * @param string $name
     * @param mixed $value
     * @param boolean $defaultScopeEnabled
     * @param string $scope
     * @param int $priority
     * @param string $locationInPage
	 * @return \muuska\asset\JsVariable
	 */
	public function createJsVariable($name, $value, $defaultScopeEnabled = true, $scope = null, $priority = null, $locationInPage = null)
	{
	    return new \muuska\asset\JsVariable($name, $value, $defaultScopeEnabled, $scope, $priority, $locationInPage);
	}
	
	/**
	 * @param string $content
     * @param boolean $jqueryWrapperEnabled
     * @param int $priority
     * @param string $locationInPage
	 * @return \muuska\asset\JsContent
	 */
	public function createJsContent($content, $jqueryWrapperEnabled = false, $priority = null, $locationInPage = null)
	{
	    return new \muuska\asset\JsContent($content, $jqueryWrapperEnabled, $priority, $locationInPage);
	}
	
	/**
	 * @param string $content
     * @param int $priority
     * @param string $locationInPage
	 * @return \muuska\asset\CssContent
	 */
	public function createCssContent($content, $priority = null, $locationInPage = null)
	{
	    return new \muuska\asset\CssContent($content, $priority, $locationInPage);
	}
	
	/**
	 * @param string $scope
     * @param int $priority
     * @param string $locationInPage
	 * @return \muuska\asset\JsScope
	 */
	public function createJsScope($scope, $priority = null, $locationInPage = null)
	{
	    return new \muuska\asset\JsScope($scope, $priority, $locationInPage);
	}
	
	/**
	 * @param string[] $scopes
     * @param int $priority
     * @param string $locationInPage
	 * @return \muuska\asset\JsArrayScope
	 */
	public function createJsArrayScope($scopes, $priority = null, $locationInPage = null)
	{
	    return new \muuska\asset\JsArrayScope($scopes, $priority, $locationInPage);
	}
	
	/**
	 * @param array $attributes
     * @param int $priority
     * @param string $locationInPage
	 * @return \muuska\asset\LinkAsset
	 */
	public function createLinkAsset($attributes, $priority = null, $locationInPage = null)
	{
	    return new \muuska\asset\LinkAsset($attributes, $priority, $locationInPage);
	}
	
	/**
	 * @param @param array $attributes
     * @param int $priority
     * @param string $locationInPage
	 * @return \muuska\asset\MetaAsset
	 */
	public function createMetaAsset($attributes, $priority = null, $locationInPage = null)
	{
	    return new \muuska\asset\MetaAsset($attributes, $priority, $locationInPage);
	}
	
	/**
	 * @param string $content
	 * @param int $priority
     * @param string $locationInPage
	 * @return \muuska\asset\StringAsset
	 */
	public function createStringAsset($content, $priority = null, $locationInPage = null)
	{
	    return new \muuska\asset\StringAsset($content, $priority, $locationInPage);
	}
	
	/**
	 * @param int $mode
     * @param bool $onlyContentEnabled
     * @param bool $minified
	 * @return \muuska\asset\AssetOutputConfig
	 */
	public function createAssetOutputConfig($mode, $onlyContentEnabled = false, $minified = false)
	{
	    return new \muuska\asset\AssetOutputConfig($mode, $onlyContentEnabled, $minified);
	}
	
	/**
	 * @param string $assetType
	 * @param string $name
	 * @param \muuska\asset\SingleAsset[] $assets
	 * @param int $priority
     * @param string $locationInPage
	 * @return \muuska\asset\AssetContainer
	 */
	public function createAssetContainer($assetType, $name, $assets, $priority = null, $locationInPage = null)
	{
	    return new \muuska\asset\AssetContainer($assetType, $name, $assets, $priority, $locationInPage);
	}
	
	/**
	 * @return \muuska\asset\DefaultAssetSetter
	 */
	public function createDefaultAssetSetter()
	{
	    return new \muuska\asset\DefaultAssetSetter();
	}
	
	/**
	 * @param \muuska\translation\loader\TranslationLoader $loader
     * @param string $name
     * @param string $lang
     * @param string $scope
     * @param boolean $defaultScopeEnabled
     * @param int $priority
     * @param string $locationInPage
	 * @return \muuska\asset\AssetTranslation
	 */
	public function createAssetTranslation(\muuska\translation\loader\TranslationLoader $loader, $name, $lang, $scope = null, $defaultScopeEnabled = true, $priority = null, $locationInPage = null)
	{
	    return new \muuska\asset\AssetTranslation($loader, $name, $lang, $scope, $defaultScopeEnabled, $priority, $locationInPage);
	}
	
	/**
	 * @param string $baseUrlPattern
	 * @param string $baseDirPattern
	 * @return \muuska\asset\DefaultRelativeAssetResolver
	 */
	public function createDefaultRelativeAssetResolver($baseUrlPattern, $baseDirPattern)
	{
	    return new \muuska\asset\DefaultRelativeAssetResolver($baseUrlPattern, $baseDirPattern);
	}
}
