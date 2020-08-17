<?php
namespace muuska\html\config;

use muuska\util\ExtraDataProvider;

interface HtmlGlobalConfig extends ExtraDataProvider{
    
    /**
     * @return bool
     */
    public function hasAssetSetter();
    
	/**
     * @return \muuska\asset\AssetSetter
     */
    public function getAssetSetter();
    
    /**
     * @return bool
     */
    public function hasTheme();
    
    /**
     * @return \muuska\util\theme\Theme
     */
    public function getTheme();
    
    /**
     * @return \muuska\asset\AssetOutputConfig
     */
    public function getAssetOutputConfig();

    /**
     * @return string
     */
    public function getLang();
}