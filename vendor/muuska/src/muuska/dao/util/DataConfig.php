<?php
namespace muuska\dao\util;

use muuska\util\App;
use muuska\util\AbstractExtraDataProvider;

abstract class DataConfig extends AbstractExtraDataProvider
{
    /**
     * @var int
     */
    protected $logicalOperator;
	
    /**
     * @var FieldRestriction[]
     */
    protected $restrictionFields = array();
    
    /**
     * @var SelectionAssociation[]
     */
    protected $selectionAssociations = array();
    
    /**
     * @var string
     */
    protected $lang;
	
	/*Fields Restrictions*/
	/**
	 * @param array $fields
	 */
	public function createRestrictionFieldsFromArray($fields) {
		foreach ($fields as $fieldKey => $value) {
			$fieldValue = null;
			$object = $this->getRestrictionFieldByKey($fieldKey, true, true);
			if(is_array($value)){
				$fieldValue = isset($value['value']) ? $value['value'] : $fieldValue;
			}else{
				$fieldValue = $value;
			}
			$fieldName = isset($value['field']) ? $value['field'] : $fieldKey;
			if(isset($value['join'])){
				$object->setJoinType($value['join']);
			}
			if(isset($value['operator'])){
				$object->setOperator($value['operator']);
			}
			$object->setFieldName($fieldName);
			$object->setValue($fieldValue);
			if(isset($value['group']) && $value['group'] && isset($value['fields'])){
				$object->createSubFieldsFromArray($value['fields']);
			}
		}
	}
	
	/**
	 * @param FieldRestriction $restrictionField
	 * @param string $key
	 */
	public function addRestrictionField(FieldRestriction $restrictionField, $key = '') {
		$key = empty($key) ? $restrictionField->getFieldName() : $key;
        $this->restrictionFields[$key] = $restrictionField;
    }
    
    /**
     * @param FieldRestriction[] $restrictionFields
     */
    public function addRestrictionFields($restrictionFields) {
        if (is_array($restrictionFields)) {
            foreach ($restrictionFields as $key => $restrictionField) {
                $finalKey = is_string($key) ? $key : '';
                $this->addRestrictionField($restrictionField, $finalKey);
            }
        }
    }
	
	/**
	 * @param string $fieldName
	 * @param mixed $value
	 * @param int $operator
	 * @param string $key
	 * @param boolean $foreign
	 * @param string $externalField
	 * @return \muuska\dao\util\FieldRestriction
	 */
	public function addRestrictionFieldFromParams($fieldName, $value, $operator = null, $key = '', $foreign = false, $externalField = null) {
		return $this->setRestrictionFieldParams($fieldName, $value, $operator, $key, $foreign, $externalField);
	}
	
	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasRestrictionField($key) {
		return (isset($this->restrictionFields[$key]));
	}
	
	/**
	 * @param array $fields
	 * @return bool
	 */
	public function hasRestrictionForFields($fields) {
	    $result = true;
	    foreach ($fields as $field) {
	        if(!$this->hasRestrictionField($field)){
	            $result = false;
	            break;
	        }
	    }
	    return $result;
	}
	
	/**
	 * @param string $key
	 * @param bool $getNewIfNotExist
	 * @param bool $addToListIfNotExist
	 * @return \muuska\dao\util\FieldRestriction
	 */
	public function getRestrictionFieldByKey($key, $getNewIfNotExist = false, $addToListIfNotExist = false) {
		$object = null;
		if($this->hasRestrictionField($key)){
			$object = $this->restrictionFields[$key];
		}else{
			$object = $getNewIfNotExist ? App::daos()->createFieldRestriction($key, null) : $object;
			if($addToListIfNotExist && ($object != null)){
				$this->addRestrictionField($object, $key);
			}
		}
		return $object;
	}
	
	/**
	 * @param string $fieldName
	 * @param mixed $value
	 * @param int $operator
	 * @param string $key
	 * @param bool $foreign
	 * @param string $externalField
	 * @return \muuska\dao\util\FieldRestriction
	 */
	public function setRestrictionFieldParams($fieldName, $value, $operator = null, $key = '', $foreign = false, $externalField = null) {
		$key = empty($key) ? $fieldName : $key;
		$object = $this->getRestrictionFieldByKey($key, true, true);
		$object->setFieldName($fieldName);
		$object->setValue($value);
		$object->setOperator($operator);
		$object->setForeign($foreign);
		$object->setExternalField($externalField);
		return $object;
	}
	
	/**
	 * @return bool
	 */
	public function hasRestrictions() {
        return !empty($this->restrictionFields);
    }
	
	
	/*Selection association*/
	/**
	 * @param SelectionAssociation $selectionAssociation
	 * @param string $key
	 */
    public function addSelectionAssociation(SelectionAssociation $selectionAssociation, $key = '') {
		$key = empty($key) ? $selectionAssociation->getFieldName() : $key;
		$this->selectionAssociations[$key] = $selectionAssociation;
	}
	
	/**
	 * @param SelectionAssociation[] $selectionAssociations
	 */
	public function addSelectionAssociations($selectionAssociations) {
	    if (is_array($selectionAssociations)) {
	        foreach ($selectionAssociations as $key => $selectionAssociation) {
	            $finalKey = is_string($key) ? $key : '';
	            $this->addSelectionAssociation($selectionAssociation, $finalKey);
	        }
	    }
	}
	
	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasAssociation($key) {
		return (isset($this->selectionAssociations[$key]));
	}
	
	/**
	 * @param string $key
	 * @param bool $getNewIfNotExist
	 * @param bool $addToListIfNotExist
	 * @return SelectionAssociation
	 */
	public function getSelectionAssociationByKey($key, $getNewIfNotExist = false, $addToListIfNotExist = false) {
		$object = null;
		if($this->hasAssociation($key)){
			$object = $this->selectionAssociations[$key];
		}else{
			$object = $getNewIfNotExist ? App::daos()->createSelectionAssociation($key, $this->langEnabled, $this->allLangsEnabled) : $object;
			if($addToListIfNotExist && ($object != null)){
				$this->addSelectionAssociation($object, $key);
			}
		}
		return $object;
	}
	
	/**
	 * @param string $fieldName
	 * @param int $joinType
	 * @param bool $retrievingEnabled
	 * @param bool $langEnabled
	 * @param bool $allLangsEnabled
	 * @return \muuska\dao\util\SelectionAssociation
	 */
	public function setSelectionAssociationParams($fieldName, $joinType = null, $retrievingEnabled = true, $langEnabled = true, $allLangsEnabled = false) {
		$object = $this->getSelectionAssociationByKey($fieldName, true, true);
		$object->setFieldName($fieldName);
		$object->setJoinType($joinType);
		$object->setRetrievingEnabled($retrievingEnabled);
		$object->setLangEnabled($langEnabled);
		$object->setAllLangsEnabled($allLangsEnabled);
		return $object;
	}
	
	/**
	 * @return array
	 */
	public function getAllFieldParameters()
	{
	    return array('restriction' => $this->restrictionFields);
	}
	
	/**
	 * @return bool
	 */
	public function hasLang()
	{
	    return !empty($this->lang);
	}
	
    /**
     * @return int
     */
    public function getLogicalOperator()
    {
        return $this->logicalOperator;
    }

    /**
     * @return FieldRestriction[]
     */
    public function getRestrictionFields()
    {
        return $this->restrictionFields;
    }

    /**
     * @return SelectionAssociation[]
     */
    public function getSelectionAssociations()
    {
        return $this->selectionAssociations;
    }

    /**
     * @param int $logicalOperator
     */
    public function setLogicalOperator($logicalOperator)
    {
        $this->logicalOperator = $logicalOperator;
    }

    /**
     * @param FieldRestriction[] $restrictionFields
     */
    public function setRestrictionFields($restrictionFields)
    {
        $this->restrictionFields = array();
        $this->addRestrictionFields($restrictionFields);
    }

    /**
     * @param SelectionAssociation[] $selectionAssociations
     */
    public function setSelectionAssociations($selectionAssociations)
    {
        $this->selectionAssociations = array();
        $this->addSelectionAssociations($selectionAssociations);
    }
    
    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
    }
}
