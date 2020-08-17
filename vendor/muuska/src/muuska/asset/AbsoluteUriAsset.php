<?php
namespace muuska\asset;

use muuska\asset\constants\AssetSubType;

class AbsoluteUriAsset extends AttributeSingleAsset
{
    /**
     * @var string
     */
    protected $location;
    
    /**
     * @param string $assetType
     * @param string $location
     * @param int $priority
     * @param string $locationInPage
     */
    public function __construct($assetType, $location, $priority = null, $locationInPage = null)
    {
        parent::__construct($assetType, AssetSubType::URI, $priority, $locationInPage);
        $this->setLocation($location);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\SingleAsset::getFinalString()
     */
    public function getFinalString(AssetOutputConfig $outputConfig = null)
    {
        return $this->getFinalStringFromAbsoluteLocation($this->location, $outputConfig);
    }
    
    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }
}
