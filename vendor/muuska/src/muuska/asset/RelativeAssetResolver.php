<?php
namespace muuska\asset;
interface RelativeAssetResolver{
    /**
     * @param \muuska\asset\RelativeAsset $asset
     * @return string
     */
    public function getFinalUrl(\muuska\asset\RelativeAsset $asset);
    
    /**
     * @param \muuska\asset\RelativeAsset $asset
     * @return string
     */
    public function getFileLocation(\muuska\asset\RelativeAsset $asset);
    
    /**
     * @param \muuska\asset\RelativeAsset $asset
     * @return string
     */
    public function getFileContent(\muuska\asset\RelativeAsset $asset);
}