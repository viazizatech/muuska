<?php
namespace muuska\model;

class ArrayModel extends AbstractModel{
    
    /**
     * @param string $field
     * @return mixed
     */
    public function getPropertyValue($field){
        return (isset($this->data['fields']) && isset($this->data['fields'][$field])) ? $this->data['fields'][$field] : null;
    }
    
    /**
     * @param string $field
     * @param mixed $value
     */
    public function setPropertyValue($field, $value){
        $this->data['fields'][$field] = $value;
    }
}