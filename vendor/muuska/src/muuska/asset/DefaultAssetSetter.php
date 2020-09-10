<?php
namespace muuska\asset;

use muuska\util\App;

class DefaultAssetSetter implements AssetSetter
{
    /**
     * @var Asset[]
     */
    protected $assets = array();
    
    /**
     * @var array
     */
    protected $formattedAssets;
    
    /**
     * @var bool
     */
    protected $assetUpdated = false;
    
    /**
     * @var array
     */
    protected $groupNames = array();
    
    /**
     * @var array
     */
    protected $containerNames = array();
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\AssetSetter::addAsset()
     */
    public function addAsset(Asset $asset)
    {
        $this->assets[] = $asset;
        $this->assetUpdated = true;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\AssetSetter::addAssetGroup()
     */
    public function addAssetGroup(AssetGroup $assetGroup)
    {
        $index = count($this->assets);
        $this->assets[$index] = $assetGroup;
        $this->groupNames[$assetGroup->getName()] = $index;
        $this->assetUpdated = true;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\AssetSetter::hasAssetGroup()
     */
    public function hasAssetGroup($name)
    {
        return (isset($this->groupNames[$name]) && isset($this->assets[$this->groupNames[$name]]));
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\AssetSetter::getAssetGroup()
     */
    public function getAssetGroup($name)
    {
        return $this->hasAssetGroup($name) ? $this->assets[$this->groupNames[$name]] : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\AssetSetter::removeAssetGroup()
     */
    public function removeAssetGroup($name)
    {
        if($this->hasAssetGroup($name)){
            $this->assets[$this->groupNames[$name]] = null;
            $this->assetUpdated = true;
        }
    }

    /**
     * {@inheritDoc}
     * @see \muuska\asset\AssetSetter::addAssetContainer()
     */
    public function addAssetContainer(AssetContainer $assetContainer)
    {
        $index = count($this->assets);
        $this->assets[$index] = $assetContainer;
        $this->containerNames[$assetContainer->getName()] = $index;
        $this->assetUpdated = true;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\asset\AssetSetter::hasAssetContainer()
     */
    public function hasAssetContainer($name)
    {
        return (isset($this->containerNames[$name]) && isset($this->assets[$this->containerNames[$name]]));
    }

    /**
     * {@inheritDoc}
     * @see \muuska\asset\AssetSetter::getAssetContainer()
     */
    public function getAssetContainer($name)
    {
        return $this->hasAssetContainer($name) ? $this->assets[$this->containerNames[$name]] : null;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\asset\AssetSetter::appendAssetToContainer()
     */
    public function appendAssetToContainer($containerName, SingleAsset $asset, $createContainerIfNotExist = true)
    {
        $container = $this->getAssetContainer($containerName);
        if($container !== null){
            $container->addAsset($asset);
        }elseif($createContainerIfNotExist){
            $container = App::assets()->createAssetContainer($asset->getAssetType(), $containerName, array($asset));
            $this->addAssetContainer($container);
        }
        $this->assetUpdated = true;
        return $container;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\AssetSetter::removeAssetContainer()
     */
    public function removeAssetContainer($name)
    {
        if($this->hasAssetContainer($name)){
            $this->assets[$this->containerNames[$name]] = null;
            $this->assetUpdated = true;
        }
    }

    /**
     * {@inheritDoc}
     * @see \muuska\asset\AssetSetter::reset()
     */
    public function reset()
    {
        $this->assets = array();
        $this->formattedAssets = null;
        $this->assetUpdated = true;
        $this->groupNames = array();
        $this->containerNames = array();
    }
    
    /**
     * @return array
     */
    protected function getFormattedAssets()
    {
        if($this->assetUpdated || ($this->formattedAssets === null)){
            $this->formattedAssets = array();
            $finalAssets = array();
            foreach($this->assets as $asset){
                if($asset !== null){
                    $tmpAssets = $asset->getSingleAssets();
                    $finalAssets = array_merge($finalAssets, $tmpAssets);
                }
            }
            
            $finalAssets = App::getTools()->sortAssets($finalAssets);
            foreach ($finalAssets as $asset){
                $this->formattedAssets[$asset->getFinalLocationInPage()][$asset->getAssetType()][(int)$asset->getPriority()][] = $asset;
            }
        }
        
        return $this->formattedAssets;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\AssetSetter::drawAssets()
     */
    public function drawAssets($locationInPage, AssetOutputConfig $outputConfig = null)
    {
        $content = '';
        $formattedAssets = $this->getFormattedAssets();
        if(isset($formattedAssets[$locationInPage])){
            $content = $this->getContentFromLocationInPageAssets($formattedAssets[$locationInPage], $outputConfig);
        }
        return $content;
    }
    
    /**
     * @param array $assetsByLocationInPage
     * @param AssetOutputConfig $outputConfig
     * @return string
     */
    protected function getContentFromLocationInPageAssets($assetsByLocationInPage, AssetOutputConfig $outputConfig = null)
    {
        $content = '';
        foreach ($assetsByLocationInPage as $priorities) {
            krsort($priorities);
            foreach ($priorities as $assets) {
                foreach ($assets as $asset) {
                    $content .= $asset->getFinalString($outputConfig) . "\n";
                }
            }
        }
        return $content;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\AssetSetter::getArrayOutput()
     */
    public function getArrayOutput(AssetOutputConfig $outputConfig = null){
        $result = array();
        $formattedAssets = $this->getFormattedAssets();
        foreach ($formattedAssets as $locationInPage => $assetsByLocationInPage) {
            $result[$locationInPage] = $this->getContentFromLocationInPageAssets($assetsByLocationInPage, $outputConfig);
        }
        return $result;
    }
}
