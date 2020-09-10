<?php
namespace muuska\dao\util;

use muuska\dao\constants\DAOValueType;

class DAOFunctionParameter
{
	/**
	 * @var mixed
	 */
	protected $value;
	
    /**
     * @var int
     */
    protected $valueType;
	
	/**
	 * @param mixed $value
	 * @param int $valueType
	 */
	public function __construct($value, $valueType = null) {
		$this->setValue($value);
		$this->setValueType($valueType);
	}
    
	/**
	 * @return bool
	 */
	public function isFieldValueType() {
		return ($this->valueType == DAOValueType::FIELD_PARAMETER);
	}
	
	/**
	 * @return bool
	 */
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
     * @param int $valueType
     */
    public function setValueType($valueType)
    {
        $this->valueType = $valueType;
    }
}
