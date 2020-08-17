<?php
namespace muuska\asset;

interface RelativeAsset
{
    /**
     * @return string
     */
    public function getLocation();
    
    /**
     * @return string
     */
    public function getLibrary();
    
    /**
     * @return string
     */
    public function getAssetType();
}
