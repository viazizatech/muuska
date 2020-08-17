<?php
namespace muuska\asset;
interface AssetSetter{
    /**
     * @param Asset $asset
     */
    public function addAsset(Asset $asset);
    
    /**
     * @param AssetGroup $assetGroup
     */
    public function addAssetGroup(AssetGroup $assetGroup);
    
    /**
     * @param string $name
     * @return bool
     */
    public function hasAssetGroup($name);
    
    /**
     * @param string $name
     */
    public function removeAssetGroup($name);
    
    /**
     * @param string $name
     * @return AssetGroup
     */
    public function getAssetGroup($name);
    
    /**
     * @param AssetContainer $assetContainer
     */
    public function addAssetContainer(AssetContainer $assetContainer);
    
    /**
     * @param string $name
     * @return bool
     */
    public function hasAssetContainer($name);
    
    /**
     * @param string $name
     */
    public function removeAssetContainer($name);
    
    /**
     * @param string $name
     * @return AssetContainer
     */
    public function getAssetContainer($name);
    
    /**
     * @param string $containerName
     * @param SingleAsset $asset
     * @param bool $createContainerIfNotExist
     * @return AssetContainer
     */
    public function appendAssetToContainer($containerName,  SingleAsset $asset, $createContainerIfNotExist = true);
    
    public function reset();
    
    /**
     * @param AssetOutputConfig $outputConfig
     * @return array
     */
    public function getArrayOutput(AssetOutputConfig $outputConfig = null);
    
    /**
     * @param int $locationInPage
     * @param AssetOutputConfig $outputConfig
     * @return string
     */
    public function drawAssets($locationInPage, AssetOutputConfig $outputConfig = null);
}