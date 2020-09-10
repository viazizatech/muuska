<?php
namespace muuska\asset;

use muuska\asset\constants\AssetSubType;
use muuska\asset\constants\AssetType;
use muuska\util\App;

class JsVariable extends AttributeSingleAsset
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var mixed
     */
    protected $value;
    
    /**
     * @var string
     */
    protected $scope;
    
    /**
     * @var bool
     */
    protected $defaultScopeEnabled;
    
    /**
     * @param string $name
     * @param mixed $value
     * @param boolean $defaultScopeEnabled
     * @param string $scope
     * @param int $priority
     * @param string $locationInPage
     */
    public function __construct($name, $value, $defaultScopeEnabled = true, $scope = null, $priority = null, $locationInPage = null)
    {
        parent::__construct(AssetType::JS, AssetSubType::VARIABLE, $priority, $locationInPage);
        $this->setName($name);
        $this->setValue($value);
        $this->setDefaultScopeEnabled($defaultScopeEnabled);
        $this->setScope($scope);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\SingleAsset::getFinalString()
     */
    public function getFinalString(AssetOutputConfig $outputConfig = null)
    {
        $result = '';
        $scope = $this->defaultScopeEnabled ? $this->getFinalScope(App::getApp()->getDefaultJsScope(), $this->scope) : $this->scope;
        if(empty($scope)){
            $result .= 'var '.$this->name;
        }else{
            $result .= $scope . '["'.$this->name.'"] ';
        }
        $result .= ' = '.json_encode($this->value);';';
        return $this->getFinalStringFromContent($result, $outputConfig);
    }
    
    
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return boolean
     */
    public function isDefaultScopeEnabled()
    {
        return $this->defaultScopeEnabled;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param string $scope
     */
    public function setScope($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @param boolean $defaultScopeEnabled
     */
    public function setDefaultScopeEnabled($defaultScopeEnabled)
    {
        $this->defaultScopeEnabled = $defaultScopeEnabled;
    }
}
