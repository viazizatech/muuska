<?php
namespace muuska\model;

abstract class AbstractModel{
    protected $data = array();
    
    public function getLangFieldsValues(){
        return isset($this->data['langFieldsValues']) ? $this->data['langFieldsValues'] : array();
    }
    public function hasAllLangPropertyValues($field){
        return (isset($this->data['langFieldsValues']) && isset($this->data['langFieldsValues'][$field]) && !empty($this->data['langFieldsValues'][$field]));
    }
    public function hasPropertyValueByLang($field, $lang){
        return ($this->hasAllLangPropertyValues($field) && isset($this->data['langFieldsValues'][$field][$lang]));
    }
    public function getAllLangsPropertyValues($field){
        return $this->hasAllLangPropertyValues($field) ? $this->data['langFieldsValues'][$field] : array();
    }
    public function getPropertyValueByLang($field, $lang){
        return $this->hasPropertyValueByLang($field, $lang) ? $this->data['langFieldsValues'][$field][$lang] : null;
    }
    
    public function setAllLangsPropertyValues($field, $values){
        $this->data['langFieldsValues'][$field] = $values;
    }
    public function setPropertyValueByLang($field, $value, $lang){
        $this->data['langFieldsValues'][$field][$lang] = $value;
    }
    
    public function setAssociated($field, $model){
        $this->data['associateds'][$field] = $model;
    }
    
    public function hasAssociated($field){
        return (isset($this->data['associateds']) && isset($this->data['associateds'][$field]));
    }
    
    /**
     * @param string $field
     * @return AbstractModel
     */
    public function getAssociated($field){
        return $this->hasAssociated($field) ? $this->data['associateds'][$field] : null;
    }
    
    public function addMultipleAssociated($associationName, $model){
        if(!isset($this->data['multipleAssociateds']) || !isset($this->data['multipleAssociateds'][$associationName])){
            $this->data['multipleAssociateds'][$associationName] = array();
        }
        $this->data['multipleAssociateds'][$associationName][] = $model;
    }
    public function setMultipleAssociatedModels($associationName, $models){
        $this->data['multipleAssociateds'][$associationName] = $models;
    }
    
    public function getMultipleAssociatedModels($associationName){
        $result = array();
        if(isset($this->data['multipleAssociateds']) && isset($this->data['multipleAssociateds'][$associationName])){
            $result = $this->data['multipleAssociateds'][$associationName];
        }
        return $result;
    }
}