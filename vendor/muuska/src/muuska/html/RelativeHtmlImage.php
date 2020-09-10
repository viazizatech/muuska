<?php
namespace muuska\html;

use muuska\asset\RelativeAsset;
use muuska\asset\constants\AssetType;
use muuska\asset\constants\AssetOutputMode;
use muuska\util\App;

class RelativeHtmlImage extends HtmlImage implements RelativeAsset{
    /**
	 * @var \muuska\asset\RelativeAssetResolver
	 */
	protected $relativeAssetResolver;
	
	/**
	 * @var string
	 */
	protected $library;
	
	/**
	 * @param \muuska\asset\RelativeAssetResolver $relativeAssetResolver
	 * @param string $location
	 * @param string $alt
	 * @param string $title
	 * @param string $library
	 */
	public function __construct(\muuska\asset\RelativeAssetResolver $relativeAssetResolver, $location, $alt = '', $title = '', $library = null) {
	    parent::__construct($location, $alt, $title);
	    $this->setRelativeAssetResolver($relativeAssetResolver);
	    $this->setLibrary($library);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlImage::getFinalSrc()
	 */
	public function getFinalSrc(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    $outputConfig = $globalConfig->getAssetOutputConfig();
	    return (($outputConfig !== null) && ($outputConfig->getMode() == AssetOutputMode::INLINE)) ? App::getFileTools()->base64EncodeImage($this->relativeAssetResolver->getFileLocation($this)) : $this->relativeAssetResolver->getFinalUrl($this);
	}
	
    /**
     * {@inheritDoc}
     * @see \muuska\asset\RelativeAsset::getLibrary()
     */
    public function getLibrary()
    {
        return $this->library;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\asset\RelativeAsset::getLocation()
     */
    public function getLocation()
    {
        return $this->getSrc();
    }

    /**
     * {@inheritDoc}
     * @see \muuska\asset\RelativeAsset::getAssetType()
     */
    public function getAssetType()
    {
        return AssetType::IMAGE;
    }
    
    /**
     * @return \muuska\asset\RelativeAssetResolver
     */
    public function getRelativeAssetResolver()
    {
        return $this->relativeAssetResolver;
    }

    /**
     * @param \muuska\asset\RelativeAssetResolver $relativeAssetResolver
     */
    public function setRelativeAssetResolver(\muuska\asset\RelativeAssetResolver $relativeAssetResolver)
    {
        $this->relativeAssetResolver = $relativeAssetResolver;
    }

    /**
     * @param string $library
     */
    public function setLibrary($library)
    {
        $this->library = $library;
    }
}