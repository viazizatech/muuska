<?php
namespace muuska\dao\util;

use muuska\util\App;

class SaveConfig extends DataConfig
{
    /**
     * @var string[]
     */
    protected $specificFields = array();
    
    /**
     * @var string[]
     */
    protected $excludedFields = array();
    
    /**
     * @var \muuska\localization\LanguageInfo[]
     */
    protected $languages = array();
    
    /**
     * @var SaveConfig[]
     */
    protected $associatedFieldsSaveConfig = array();
    
    /**
     * @var MultipleSaveAssociation[]
     */
    protected $multipleSaveAssociations = array();
    
    /**
     * @var bool
     */
    protected $langEnabled = true;
    
    /**
     * @param string $lang
     * @param \muuska\localization\LanguageInfo[] $languages
     */
    public function __construct($lang = null, $languages = array()) {
        $this->setLang($lang);
        $this->setLanguages($languages);
    }
    
    /**
     * @return bool
     */
    public function hasAssociatedFieldsSaveConfig() {
        return !empty($this->associatedFieldsSaveConfig);
    }
    
    /**
     * @param string $field
     * @return bool
     */
    public function hasAssociatedFieldSaveConfig($field) {
        return isset($this->associatedFieldsSaveConfig[$field]);
    }
    
    /**
     * @param MultipleSaveAssociation $multipleSaveAssociation
     */
    public function addMultipleSaveAssociation(MultipleSaveAssociation $multipleSaveAssociation) {
        $this->multipleSaveAssociations[$multipleSaveAssociation->getAssociationName()] = $multipleSaveAssociation;
    }
    
    /**
     * @param MultipleSaveAssociation[] $multipleSaveAssociations
     */
    public function addMultipleSaveAssociations($multipleSaveAssociations) {
        if (is_array($multipleSaveAssociations)) {
            foreach ($multipleSaveAssociations as $multipleSaveAssociation) {
                $this->addMultipleSaveAssociation($multipleSaveAssociation);
            }
        }
    }
    
    /**
     * @param string $associationName
     * @return \muuska\dao\util\MultipleSaveAssociation
     */
    public function createMultipleSaveAssociation($associationName) {
        $association = App::daos()->createMultipleSaveAssociation($associationName, $this->lang, $this->languages);
        $this->addMultipleSaveAssociation($association);
        return $association;
    }
    
    /**
     * @param string $associationName
     * @return bool
     */
    public function hasMultipleSaveAssociation($associationName) {
        return isset($this->multipleSaveAssociations[$associationName]);
    }
    /**
     * @return bool
     */
    public function hasMultipleSaveAssociations() {
        return !empty($this->multipleSaveAssociations);
    }
    
    /**
     * @param string $field
     * @param SaveConfig $saveConfig
     */
    public function addAssociatedFieldSaveConfig($field, SaveConfig $saveConfig) {
        $this->associatedFieldsSaveConfig[$field] = $saveConfig;
    }
    
    /**
     * @param SaveConfig[] $saveConfigs
     */
    public function addAssociatedFieldSaveConfigs($saveConfigs) {
        if (is_array($saveConfigs)) {
            foreach ($saveConfigs as $field => $saveConfig) {
                $this->addAssociatedFieldSaveConfig($field, $saveConfig);
            }
        }
    }
    
    /**
     * @param string $field
     * @return SaveConfig
     */
    public function getAssociatedFieldSaveConfig($field) {
        return $this->hasAssociatedFieldSaveConfig($field) ? $this->associatedFieldsSaveConfig[$field] : null;
    }
    
    /**
     * @param string $field
     * @return SaveConfig
     */
    public function createAssociatedFieldSaveConfig($field, $addToList = true) {
        $saveConfig = App::daos()->createSaveConfig($this->lang, $this->languages);
        if($addToList){
            $this->addAssociatedFieldSaveConfig($field, $saveConfig);
        }
        return $saveConfig;
    }
    
    /**
     * @param string $field
     */
    public function addSpecificField($field) {
        $this->specificFields[] = $field;
    }
    
    /**
     * @param string $field
     */
    public function addExcludedField($field) {
        $this->excludedFields[] = $field;
    }
    
    /**
     * @param string[] $fields
     */
    public function addExcludedFields($fields) {
        if(is_array($fields)){
            foreach ($fields as $field) {
                $this->addExcludedField($field);
            }
        }
    }
    
    /**
     * @param string[] $fields
     */
    public function addSpecificFields($fields) {
        if(is_array($fields)){
            foreach ($fields as $field) {
                $this->addSpecificField($field);
            }
        }
    }
    
    /**
     * @return bool
     */
    public function hasSpecificFields(){
        return !empty($this->specificFields);
    }
    
    /**
     * @return bool
     */
    public function hasExcludedFields(){
        return !empty($this->excludedFields);
    }
    
    /**
     * @param array $fields
     * @return array
     */
    public function getFinalFields($fields){
        $result = $fields;
        if($this->hasSpecificFields()){
            $result = $this->getSpecificFields();
        }elseif($this->hasExcludedFields()){
            $result = array();
            foreach ($fields as $field) {
                if(!in_array($field, $this->excludedFields)){
                    $result[] = $field;
                }
            }
        }
        return $result;
    }
	
    /**
     * @return boolean
     */
    public function isMultipleAssociation() {
        return false;
    }
    
    /**
     * @return string[]
     */
    public function getSpecificFields()
    {
        return $this->specificFields;
    }

    /**
     * @return string[]
     */
    public function getExcludedFields()
    {
        return $this->excludedFields;
    }

    /**
     * @return \muuska\localization\LanguageInfo[]
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @return \muuska\dao\util\SaveConfig[]
     */
    public function getAssociatedFieldsSaveConfig()
    {
        return $this->associatedFieldsSaveConfig;
    }

    /**
     * @return \muuska\dao\util\MultipleSaveAssociation[]
     */
    public function getMultipleSaveAssociations()
    {
        return $this->multipleSaveAssociations;
    }

    /**
     * @param string[] $specificFields
     */
    public function setSpecificFields($specificFields)
    {
        $this->specificFields = array();
        $this->addSpecificFields($specificFields);
    }

    /**
     * @param string[] $excludedFields
     */
    public function setExcludedFields($excludedFields)
    {
        $this->excludedFields = array();
        $this->addExcludedFields($excludedFields);
    }

    /**
     * @param \muuska\localization\LanguageInfo[] $languages
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    /**
     * @param SaveConfig[] $associatedFieldsSaveConfig
     */
    public function setAssociatedFieldsSaveConfig($associatedFieldsSaveConfig)
    {
        $this->associatedFieldsSaveConfig = array();
        $this->addAssociatedFieldSaveConfigs($associatedFieldsSaveConfig);
    }

    /**
     * @param MultipleSaveAssociation[] $multipleSaveAssociations
     */
    public function setMultipleSaveAssociations($multipleSaveAssociations)
    {
        $this->multipleSaveAssociations = array();
        $this->addMultipleSaveAssociations($multipleSaveAssociations);
    }
    
    /**
     * @return boolean
     */
    public function isLangEnabled()
    {
        return $this->langEnabled;
    }

    /**
     * @param boolean $langEnabled
     */
    public function setLangEnabled($langEnabled)
    {
        $this->langEnabled = $langEnabled;
    }
}
