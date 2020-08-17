<?php
namespace muuska\asset;

interface Asset
{
    /**
     * @return int
     */
    public function getPosition();
    
    /**
     * @return SingleAsset[]
     */
    public function getSingleAssets();
}
