<?php
namespace muuska\asset;

use muuska\asset\constants\AssetType;
use muuska\asset\constants\LocationInPage;

abstract class SingleAsset implements Asset
{
    /**
     * @var string
     */
    protected $assetType;
    
    /**
     * @var string
     */
    protected $subType;
    
    /**
     * @var string
     */
    protected $locationInPage;
    
    /**
     * @var int
     */
    protected $position;
    
    /**
     * @var int
     */
    protected $priority;
    
    /**
     * @param string $assetType
     * @param string $subType
     * @param int $priority
     * @param string $locationInPage
     */
    public function __construct($assetType, $subType, $priority = null, $locationInPage = null){
        $this->setAssetType($assetType);
        $this->setSubType($subType);
        $this->setPriority($priority);
        $this->setLocationInPage($locationInPage);
    }
    
    /**
     * @param AssetOutputConfig $outputConfig
     * @return string
     */
    abstract public function getFinalString(AssetOutputConfig $outputConfig = null);
    
    /**
     * @return string
     */
    public function getFinalLocationInPage()
    {
        $locationInPage = $this->getLocationInPage();
        if(empty($locationInPage)){
            $headAssetTypes = array(AssetType::CSS, AssetType::FONT, AssetType::META, AssetType::LINK);
            if(in_array($this->assetType, $headAssetTypes)){
                $locationInPage = LocationInPage::HEAD;
            }else{
                $locationInPage = LocationInPage::BEFORE_BODY_END;
            }
        }
        return $locationInPage;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\Asset::getSingleAssets()
     */
    public function getSingleAssets(){
        return array($this);
    }
    /**
     * @return string
     */
    public function getAssetType()
    {
        return $this->assetType;
    }

    /**
     * @return string
     */
    public function getSubType()
    {
        return $this->subType;
    }

    /**
     * @return string
     */
    public function getLocationInPage()
    {
        return $this->locationInPage;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param string $assetType
     */
    public function setAssetType($assetType)
    {
        $this->assetType = $assetType;
    }

    /**
     * @param string $subType
     */
    public function setSubType($subType)
    {
        $this->subType = $subType;
    }

    /**
     * @param string $locationInPage
     */
    public function setLocationInPage($locationInPage)
    {
        $this->locationInPage = $locationInPage;
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @param int $priority
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
    }
}
