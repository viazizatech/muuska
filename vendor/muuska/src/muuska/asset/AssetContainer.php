<?php
namespace muuska\asset;

use muuska\asset\constants\AssetOutputMode;
use muuska\asset\constants\AssetSubType;
use muuska\util\App;

class AssetContainer extends AttributeSingleAsset
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var SingleAsset[]
     */
    protected $assets;
    
   /**
    * @param string $assetType
    * @param string $name
    * @param SingleAsset[] $assets
    * @param int $priority
    * @param string $locationInPage
    */
    public function __construct($assetType, $name, $assets, $priority = null, $locationInPage = null)
    {
        parent::__construct($assetType, AssetSubType::CONTAINER, $priority, $locationInPage);
        $this->setName($name);
        $this->setAssets($assets);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\SingleAsset::getFinalString()
     */
    public function getFinalString(AssetOutputConfig $outputConfig = null)
    {
        $content = '';
        $assets = App::getTools()->sortAssets($this->assets);
        $newOutputConfig = App::assets()->createAssetOutputConfig(AssetOutputMode::INLINE, true);
        foreach ($assets as $singleAsset) {
            $content .= $singleAsset->getFinalString($newOutputConfig) . "\n";
        }
        return $this->getFinalStringFromContent($content, $outputConfig);
    }
    
    /**
     * @param SingleAsset $asset
     */
    public function addAsset(SingleAsset $asset){
        $this->assets[] = $asset;
    }
    
    /**
     * @param SingleAsset[] $assets
     */
    public function addAssets($assets){
        if(is_array($assets)){
            foreach ($assets as $asset) {
                $this->addAsset($asset);
            }
        }
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return SingleAsset[]
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param SingleAsset[] $assets
     */
    public function setAssets($assets)
    {
        $this->assets = array();
        $this->addAssets($assets);
    }
}
