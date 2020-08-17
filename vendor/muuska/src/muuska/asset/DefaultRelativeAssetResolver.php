<?php
namespace muuska\asset;

use muuska\asset\constants\AssetType;

class DefaultRelativeAssetResolver implements RelativeAssetResolver{
    /**
     * @var string
     */
    protected $baseUrlPattern;
    
    /**
     * @var string
     */
    protected $baseDirPattern;
    
    /**
     * @param string $baseUrlPattern
     * @param string $baseDirPattern
     */
    public function __construct($baseUrlPattern, $baseDirPattern) {
        $this->baseUrlPattern = $baseUrlPattern;
        $this->baseDirPattern = $baseDirPattern;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\RelativeAssetResolver::getFinalUrl()
     */
    public function getFinalUrl(\muuska\asset\RelativeAsset $asset){
        return $this->getLocationFromPattern($this->baseUrlPattern, $asset);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\RelativeAssetResolver::getFileLocation()
     */
    public function getFileLocation(\muuska\asset\RelativeAsset $asset){
        return $this->getLocationFromPattern($this->baseDirPattern, $asset);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\RelativeAssetResolver::getFileContent()
     */
    public function getFileContent(\muuska\asset\RelativeAsset $asset){
        $file = $this->getFileLocation($asset);
        return file_exists($file) ? file_get_contents($file) : '';
    }
    
    /**
     * @param \muuska\asset\RelativeAsset $asset
     * @return string
     */
    public function getRelativeFolder(\muuska\asset\RelativeAsset $asset){
        $result = '';
        $assetType = $asset->getAssetType();
        $library = $asset->getLibrary();
        if(!empty($library)){
            $result .= 'lib/'.$library;
        }else{
            $assetDirsDef = array(AssetType::CSS => 'css', AssetType::JS => 'js', AssetType::FONT => 'font', AssetType::IMAGE => 'img');
            if(isset($assetDirsDef[$assetType])){
                $result .= $assetDirsDef[$assetType];
            }
        }
        return $result;
    }
    
    /**
     * @param string $pattern
     * @param \muuska\asset\RelativeAsset $asset
     * @return string
     */
    public function getLocationFromPattern($pattern, \muuska\asset\RelativeAsset $asset){
        return str_replace('{type}', $this->getRelativeFolder($asset), $pattern) . $asset->getLocation();
    }
}