<?php
namespace muuska\asset;

use muuska\asset\constants\AssetType;
use muuska\util\App;

abstract class AttributeSingleAsset extends SingleAsset
{
    /**
     * @var array
     */
    protected $attributes;
    
    /**
     * @param string $location
     * @param AssetOutputConfig $outputConfig
     * @return string
     */
    protected function getFinalStringFromAbsoluteLocation($location, AssetOutputConfig $outputConfig = null)
    {
        $result = '';
        if($this->assetType == AssetType::JS){
            $result = '<script type="text/javascript" src="'.$location.'"'.$this->drawAttributes().'></script>';
        }elseif($this->assetType == AssetType::CSS){
            $result = '<link rel="stylesheet" href="'.$location.'"'.$this->drawAttributes().'>';
        }elseif($this->assetType == AssetType::LINK){
            $result = '<link href="'.$location.'"'.$this->drawAttributes().'>';
        }
        return $result;
    }
    
    /**
     * @param string $content
     * @param AssetOutputConfig $outputConfig
     * @return string
     */
    protected function getFinalStringFromContent($content, AssetOutputConfig $outputConfig = null)
    {
        $result = '';
        $onlyContentEnabled = ($outputConfig !== null) ? $outputConfig->isOnlyContentEnabled() : false;
        if($this->assetType == AssetType::JS){
            $result = ($onlyContentEnabled ? '' : '<script type="text/javascript"'.$this->drawAttributes().'>').$content.($onlyContentEnabled ? '' : '</script>');
        }elseif($this->assetType == AssetType::CSS){
            $result = ($onlyContentEnabled ? '' : '<style type="text/css"'.$this->drawAttributes().'>').$content.($onlyContentEnabled ? '' : '</style>');
        }
        return $result;
    }
    
    /**
     * @param string $defaultJsScope
     * @param string $scope
     * @return string
     */
    protected function getFinalScope($defaultJsScope, $scope)
    {
        if(empty($scope)){
            $scope = $defaultJsScope;
        }elseif (!empty($defaultJsScope)){
            $scope = $defaultJsScope . (App::getStringTools()->startsWith('[', $scope) ? '' : '.').$scope;
        }
        return $scope;
    }
    
    /**
     * @param bool $addSpace
     * @param array $attributesToExclude
     * @return string
     */
    public function drawAttributes($addSpace = true, $excludedAttributes = array()) {
        $result = '';
        $first = true;
        if(is_array($this->attributes)){
            foreach ($this->attributes as $key => $value) {
                if(!is_array($excludedAttributes) || !in_array($key, $excludedAttributes)){
                    if(!$first){
                        $result .= ' ';
                    }
                    $result .= $key. (($value === null) ? '' : '="' . $value . '"');
                    $first = false;
                }
            }
        }
        return (($addSpace && empty($result)) ? ' ' . $result : $result);
    }
    
    /**
     * @param string $name
     * @return bool
     */
    public function hasAttribute($name){
        return isset($this->attributes[$name]);
    }
    
    /**
     * @param string $name
     * @return mixed
     */
    public function getAttribute($name){
        return $this->hasAttribute($name) ? $this->attributes[$name] : null;
    }
    
    /**
     * @param string $name
     * @param mixed $value
     */
    public function addAttribute($name, $value){
        $this->setAttribute($name, $value);
    }
    
    /**
     * @param array $params
     */
    public function addAttributes($attributes){
        if(is_array($attributes)){
            foreach ($attributes as $key => $value) {
                $this->addAttribute($key, $value);
            }
        }
    }
    
    /**
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value){
        $this->attributes[$name] = $value;
    }
    
    /**
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes($attributes)
    {
        $this->attributes = array();
        $this->addAttributes($attributes);
    }
}
