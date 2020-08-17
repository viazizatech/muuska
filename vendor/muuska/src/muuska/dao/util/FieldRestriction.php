<?php
namespace muuska\dao\util;

use muuska\dao\constants\DAOValueType;
use muuska\util\App;

class FieldRestriction extends FieldParameter
{
    /**
     * @var mixed
     */
    protected $value;
    
    /**
     * @var int
     */
    protected $logicalOperator;
    
    /**
     * @var int
     */
    protected $operator;
    
    /**
     * @var FieldRestriction[]
     */
    protected $subFields;
    
	/**
	 * @var int
	 */
	protected $joinType;
	
	/**
	 * @var int
	 */
	protected $valueType;
	
	public function __construct($fieldName, $value, $operator = null) {
		$this->setFieldName($fieldName);
		$this->setValue($value);
		$this->setOperator($operator);
	}
	
	/**
	 * @param array $fields
	 */
	public function createSubFieldsFromArray($fields) {
		foreach ($fields as $fieldKey => $value) {
			$fieldValue = null;
			$object = $this->getSubFieldByKey($fieldKey, true, true);
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
	 * @param FieldRestriction $subField
	 * @param string $key
	 */
	public function addSubField(FieldRestriction $subField, $key = '') {
		$key = empty($key) ? $subField->getFieldName() : $key;
		$this->subFields[$key] = $subField;
	}
	
	/**
	 * @param FieldRestriction[] $subFields
	 */
	public function addSubFields($subFields) {
	    if (is_array($subFields)) {
	        foreach ($subFields as $key => $restrictionField) {
	            $finalKey = is_string($key) ? $key : '';
	            $this->addSubField($restrictionField, $finalKey);
	        }
	    }
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
	public function addSubFieldFromParams($fieldName, $value, $operator = null, $key = '', $foreign = false, $externalField = null) {
		return $this->setSubFieldParams($fieldName, $value, $operator, $key, $foreign, $externalField);
	}
	
	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasSubField($key) {
		return (isset($this->subFields[$key]));
	}
	
	/**
	 * @param string $key
	 * @param bool $getNewIfNotExist
	 * @param bool $addToListIfNotExist
	 * @return FieldRestriction
	 */
	public function getSubFieldByKey($key, $getNewIfNotExist = false, $addToListIfNotExist = false) {
		$object = null;
		if($this->hasSubField($key)){
			$object = $this->subFields[$key];
		}else{
			$object = $getNewIfNotExist ? App::daos()->createFieldRestriction($key, null) : $object;
			if($addToListIfNotExist && ($object != null)){
				$this->addSubField($object, $key);
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
	 * @return FieldRestriction
	 */
	public function setSubFieldParams($fieldName, $value, $operator = null, $key = '', $foreign = false, $externalField = null) {
		$key = empty($key) ? $fieldName : $key;
		$object = $this->getSubFieldByKey($key, true, true);
		$object->setFieldName($fieldName);
		$object->setValue($value);
		$object->setOperator($operator);
		$object->setForeign($foreign);
		$object->setExternalField($externalField);
		return $object;
	}
	
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\util\FieldParameter::hasJoinType()
	 */
	public function hasJoinType() {
		return !empty($this->joinType);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\util\FieldParameter::hasSubFields()
	 */
	public function hasSubFields() {
		return !empty($this->subFields);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\util\FieldParameter::hasValue()
	 */
	public function hasValue() {
		return true;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\dao\util\FieldParameter::isFieldValueType()
	 */
	public function isFieldValueType() {
		return ($this->valueType == DAOValueType::FIELD_PARAMETER);
	}
	public function isDaoFunctionValueType() {
		return ($this->valueType == DAOValueType::DAO_FUNCTION);
	}
	
    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getLogicalOperator()
    {
        return $this->logicalOperator;
    }

    /**
     * @return int
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @return FieldRestriction[]
     */
    public function getSubFields()
    {
        return $this->subFields;
    }

    /**
     * @return int
     */
    public function getJoinType()
    {
        return $this->joinType;
    }

    /**
     * @return int
     */
    public function getValueType()
    {
        return $this->valueType;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param int $logicalOperator
     */
    public function setLogicalOperator($logicalOperator)
    {
        $this->logicalOperator = $logicalOperator;
    }

    /**
     * @param int $operator
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    }

    /**
     * @param FieldRestriction[] $subFields
     */
    public function setSubFields($subFields)
    {
        $this->subFields = array();
        $this->addSubFields($subFields);
    }

    /**
     * @param int $joinType
     */
    public function setJoinType($joinType)
    {
        $this->joinType = $joinType;
    }

    /**
     * @param int $valueType
     */
    public function setValueType($valueType)
    {
        $this->valueType = $valueType;
    }
}
