<?php
namespace muuska\asset;

use muuska\asset\constants\AssetSubType;
use muuska\asset\constants\AssetType;
use muuska\util\App;

class AssetTranslation extends AttributeSingleAsset
{
    /**
     * @var string
     */
    protected $lang;
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var \muuska\translation\loader\TranslationLoader
     */
    protected $loader;
    
    /**
     * @var string
     */
    protected $scope;
    
    /**
     * @var bool
     */
    protected $defaultScopeEnabled;
    
    /**
     * @param \muuska\translation\loader\TranslationLoader $loader
     * @param string $name
     * @param string $lang
     * @param string $scope
     * @param boolean $defaultScopeEnabled
     * @param int $priority
     * @param string $locationInPage
     */
    public function __construct(\muuska\translation\loader\TranslationLoader $loader, $name, $lang, $scope = null, $defaultScopeEnabled = true, $priority = null, $locationInPage = null)
    {
        parent::__construct(AssetType::JS, AssetSubType::TRANSLATION, $priority, $locationInPage);
        $this->setLoader($loader);
        $this->setName($name);
        $this->setLang($lang);
        $this->setScope($scope);
        $this->setDefaultScopeEnabled($defaultScopeEnabled);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\asset\SingleAsset::getFinalString()
     */
    public function getFinalString(AssetOutputConfig $outputConfig = null)
    {
        $result = '';
        $translations = array();
        if($this->loader !== null){
            $translations = $this->loader->getTranslations($this->lang);
        }
        if(!empty($translations)){
            $scope = $this->defaultScopeEnabled ? $this->getFinalScope(App::getApp()->getTranslationDefaultJsScope(), $this->scope) : $this->scope;
            $result = $scope . '["'.$this->name.'"] ' . ' = '.json_encode($this->value);';';
            $result = $this->getFinalStringFromContent($result, $outputConfig);
        }
        return $result;
    }
    
    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return \muuska\translation\loader\TranslationLoader
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param \muuska\translation\loader\TranslationLoader $loader
     */
    public function setLoader(\muuska\translation\loader\TranslationLoader $loader)
    {
        $this->loader = $loader;
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
