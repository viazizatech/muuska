<?php
namespace muuska\asset;

use muuska\asset\constants\AssetOutputMode;
use muuska\asset\constants\AssetSubType;

class RelativeUriAsset extends AttributeSingleAsset implements RelativeAsset
{
    /**
     * @var string
     */
    protected $location;
    
    /**
     * @var RelativeAssetResolver
     */
    protected $resolver;
    
    /**
     * @var string
     */
    protected $library;
    
    /**
     * @param string $assetType
     * @param string $location
     * @param RelativeAssetResolver $resolver
     * @param string $library
     * @param int $priority
     * @param string $locationInPage
     */
    public function __construct($assetType, $location, RelativeAssetResolver $resolver, $library = null, $priority = null, $locationInPage = null)
    {
        parent::__construct($assetType, AssetSubType::URI, $priority, $locationInPage);
        $this->setLocation($location);
        $this->setResolver($resolver);
        $this->setLibrary($library);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\SingleAsset::getFinalString()
     */
    public function getFinalString(AssetOutputConfig $outputConfig = null)
    {
        $result = '';
        $mode = ($outputConfig === null) ? null : $outputConfig->getMode();
        if(empty($mode) || ($mode == AssetOutputMode::NORMAL)){
            $result =  $this->getFinalStringFromAbsoluteLocation($this->resolver->getFinalUrl($this), $outputConfig);
        }else{
            $result = $this->getFinalStringFromContent($this->resolver->getFileContent($this), $outputConfig);
        }
        return $result;
    }
    
    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return \muuska\asset\RelativeAssetResolver
     */
    public function getResolver()
    {
        return $this->resolver;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @param \muuska\asset\RelativeAssetResolver $resolver
     */
    public function setResolver(RelativeAssetResolver $resolver)
    {
        $this->resolver = $resolver;
    }
    
    /**
     * @return string
     */
    public function getLibrary()
    {
        return $this->library;
    }

    /**
     * @param string $library
     */
    public function setLibrary($library)
    {
        $this->library = $library;
    }
}
