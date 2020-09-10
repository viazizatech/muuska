<?php
namespace muuska\html\config;

use muuska\util\AbstractExtraDataProvider;

class DefaultHtmlGlobalConfig extends AbstractExtraDataProvider implements HtmlGlobalConfig{
	/**
	 * @var \muuska\asset\AssetSetter
	 */
	protected $assetSetter;
	
	/**
	 * @var string
	 */
	protected $lang;
	
	/**
	 * @var \muuska\util\theme\Theme
	 */
	protected $theme;
	
	/**
	 * @var \muuska\asset\AssetOutputConfig
	 */
	protected $assetOutputConfig;
	
	public function __construct($lang, \muuska\asset\AssetSetter $assetSetter = null, \muuska\util\theme\Theme $theme = null, \muuska\asset\AssetOutputConfig $assetOutputConfig = null) {
	    $this->lang = $lang;
	    $this->assetSetter = $assetSetter;
	    $this->theme = $theme;
	    $this->assetOutputConfig = $assetOutputConfig;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\config\HtmlGlobalConfig::hasAssetSetter()
	 */
	public function hasAssetSetter()
	{
	    return ($this->assetSetter !== null);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\config\HtmlGlobalConfig::hasTheme()
	 */
	public function hasTheme()
	{
	    return ($this->theme !== null);
	}
	
    /**
     * {@inheritDoc}
     * @see \muuska\html\config\HtmlGlobalConfig::getAssetSetter()
     */
    public function getAssetSetter()
    {
        return $this->assetSetter;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\html\config\HtmlGlobalConfig::getLang()
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\html\config\HtmlGlobalConfig::getTheme()
     */
    public function getTheme()
    {
        return $this->theme;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\config\HtmlGlobalConfig::getAssetOutputConfig()
     */
    public function getAssetOutputConfig(){
        return $this->assetOutputConfig;
    }
}