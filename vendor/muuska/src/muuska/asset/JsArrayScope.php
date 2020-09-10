<?php
namespace muuska\asset;

use muuska\asset\constants\AssetSubType;
use muuska\asset\constants\AssetType;
use muuska\util\App;

class JsArrayScope extends AttributeSingleAsset
{
    /**
     * @var string[]
     */
    protected $scopes;
    
    /**
     * @param string[] $scopes
     * @param int $priority
     * @param string $locationInPage
     */
    public function __construct($scopes, $priority = null, $locationInPage = null)
    {
        parent::__construct(AssetType::JS, AssetSubType::SCOPE, $priority, $locationInPage);
        $this->setScopes($scopes);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\SingleAsset::getFinalString()
     */
    public function getFinalString(AssetOutputConfig $outputConfig = null)
    {
        $content = '';
        $scopeTree = '';
        $first = true;
        foreach ($this->scopes as $scope) {
            $prefix = '';
            if($first){
                $scopeTree .= $scope;
                if((strpos($scope, '.') === false) && (strpos($scope, '[') === false)){
                    $prefix = 'var ';
                }
                $first = false;
            }else{
                $scopeTree .= '["'.$scope.'"]';
            }
            $content .= $prefix . $scopeTree . ' = '.$scopeTree.' || {};';
        }
        return $this->getFinalStringFromContent($content, $outputConfig);
    }
    
    /**
     * @return string
     */
    public function getStringScope()
    {
        return App::getTools()->getScopeFromArray($this->scopes);
    }
    
    /**
     * @return string[]
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * @param string[] $scopes
     */
    public function setScopes($scopes)
    {
        $this->scopes = $scopes;
    }
}
