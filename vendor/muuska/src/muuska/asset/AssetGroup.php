<?php
namespace muuska\asset;

class AssetGroup implements Asset
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var SingleAsset[]
     */
    protected $singleAssets;
    
    /**
     * @var int
     */
    protected $position;
    
    /**
     * @param string $name
     * @param SingleAsset[] $singleAssets
     */
    public function __construct($name, $singleAssets = array())
    {
        $this->setName($name);
        $this->setSingleAssets($singleAssets);
    }
    
    /**
     * @param SingleAsset $singleAsset
     */
    public function addAsset(SingleAsset $singleAsset) {
        $this->singleAssets[] = $singleAsset;
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
     * {@inheritDoc}
     * @see \muuska\asset\Asset::getSingleAssets()
     */
    public function getSingleAssets()
    {
        return $this->singleAssets;
    }

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param SingleAsset[] $singleAssets
     */
    public function setSingleAssets($singleAssets)
    {
        $this->singleAssets = array();
        $this->addAssets($singleAssets);
    }

    /**
     * @param int $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}
