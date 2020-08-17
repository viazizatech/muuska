<?php
namespace muuska\dao\util;

use muuska\util\App;

class MultipleSaveAssociation extends SaveConfig
{
    /**
     * @var string
     */
    protected $associationName;
    
    /**
     * @var SaveConfig[]
     */
    protected $modelSpecificSaveConfigs;
	
    /**
     * @param string $associationName
     * @param string $lang
     * @param \muuska\localization\LanguageInfo[] $languages
     */
    public function __construct($associationName, $lang, $languages = array()) {
        parent::__construct($lang, $languages);
        $this->setAssociationName($associationName);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\util\SaveConfig::isMultipleAssociation()
	 */
	public function isMultipleAssociation() {
	    return true;
	}
	
	/**
	 * @param string $key
	 * @param boolean $createNewIfNotExist
	 * @return SaveConfig
	 */
	public function getModelSpecificSaveConfig($key, $createNewIfNotExist = true) {
	    $result = null;
	    if($this->hasModelSpecificSaveConfig($key)){
	        $result = $this->modelSpecificSaveConfigs[$key];
	    }elseif($createNewIfNotExist){
	        $result = $this->createModelSpecificSaveConfig($key);
	    }
	    return $result;
	}
	
	/**
	 * @param string $key
	 * @param SaveConfig $saveConfig
	 */
	public function addModelSpecificSaveConfig($key, SaveConfig $saveConfig) {
	    $this->modelSpecificSaveConfigs[$key] = $saveConfig;
	}
	
	/**
	 * @param SaveConfig[] $saveConfigs
	 */
	public function addModelSpecificSaveConfigs(SaveConfig $saveConfigs) {
	    if (is_array($saveConfigs)) {
	        foreach ($saveConfigs as $key => $saveConfig) {
	            $this->addModelSpecificSaveConfig($key, $saveConfig);
	        }
	    }
	}
	
	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasModelSpecificSaveConfig($key) {
	    return isset($this->modelSpecificSaveConfigs[$key]);
	}
	
	/**
	 * @param string $key
	 * @return \muuska\dao\util\SaveConfig
	 */
	public function createModelSpecificSaveConfig($key) {
	    $result = App::daos()->createSaveConfig($this->lang, $this->languages);
	    $result->setExcludedFields($this->getExcludedFields());
	    $result->setSpecificFields($this->getSpecificFields());
	    $this->addModelSpecificSaveConfig($key, $result);
	    return $result;
	}
	
    /**
     * @return string
     */
    public function getAssociationName()
    {
        return $this->associationName;
    }

    /**
     * @param string $associationName
     */
    public function setAssociationName($associationName)
    {
        $this->associationName = $associationName;
    }
    /**
     * @return multitype:\muuska\dao\util\SaveConfig 
     */
    public function getModelSpecificSaveConfigs()
    {
        return $this->modelSpecificSaveConfigs;
    }

    /**
     * @param \muuska\dao\util\SaveConfig[] $modelSpecificSaveConfigs
     */
    public function setModelSpecificSaveConfigs($modelSpecificSaveConfigs)
    {
        $this->modelSpecificSaveConfigs = array();
        $this->addModelSpecificSaveConfigs($modelSpecificSaveConfigs);
    }
}