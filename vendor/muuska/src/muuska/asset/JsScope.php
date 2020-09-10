<?php
namespace muuska\asset;

use muuska\asset\constants\AssetSubType;
use muuska\asset\constants\AssetType;

class JsScope extends AttributeSingleAsset
{
    /**
     * @var string
     */
    protected $scope;
    
    /**
     * @param string $scope
     * @param int $priority
     * @param string $locationInPage
     */
    public function __construct($scope, $priority = null, $locationInPage = null)
    {
        parent::__construct(AssetType::JS, AssetSubType::SCOPE, $priority, $locationInPage);
        $this->setScope($scope);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\SingleAsset::getFinalString()
     */
    public function getFinalString(AssetOutputConfig $outputConfig = null)
    {
        return $this->getFinalStringFromContent($this->scope . ' = '.$this->scope.' || {};', $outputConfig);
    }
    
    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }
}
