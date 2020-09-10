<?php
namespace muuska\dao\util;

use muuska\util\App;
use muuska\util\AbstractExtraDataProvider;

class FieldParameter extends AbstractExtraDataProvider
{
	/**
	 * @var string
	 */
	protected $fieldName;
	
    /**
     * @var string
     */
    protected $externalField;
    
    /**
     * @var bool
     */
    protected $foreign;
    
	/**
	 * @var DAOFunction
	 */
	protected $daoFunction;
	
	/**
	 * @var FieldParameter
	 */
	protected $subExternalField;
	
	/**
	 * @param string $fieldName
	 * @param boolean $foreign
	 * @param string $externalField
	 */
	public function __construct($fieldName, $foreign = false, $externalField = null) {
		$this->setFieldName($fieldName);
		$this->setForeign($foreign);
		$this->setExternalField($externalField);
	}
	
	/**
	 * @return bool
	 */
	public function hasJoinType() {
		return false;
	}
	
	/**
	 * @return bool
	 */
	public function hasSubFields() {
		return false;
	}
	
	/**
	 * @return bool
	 */
	public function hasDaoFunction() {
		return ($this->daoFunction !== null);
	}
	
	/**
	 * @return bool
	 */
	public function hasValue() {
		return false;
	}
	
	/**
	 * @return bool
	 */
	public function isFieldValueType() {
		return false;
	}
	
	/**
	 * @param string $code
	 * @return \muuska\dao\util\DAOFunction
	 */
	public function createDaoFunctionFromCode($code) {
		$daoFunction = App::daos()->createDAOFunction($code);
		$this->setDaoFunction($daoFunction);
		return $daoFunction;
	}
	
	/**
	 * @return bool
	 */
	public function hasSubExternalField() {
		return ($this->subExternalField !== null);
	}
	
	/**
	 * @param string $externalField
	 * @return \muuska\dao\util\FieldParameter
	 */
	public function setSubExternalFieldFromParams($externalField) {
	    $object = App::daos()->createFieldParameter($this->getExternalField(), true, $externalField);
		$this->subExternalField = $object;
		return $object;
	}
	
    /**
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * @return string
     */
    public function getExternalField()
    {
        return $this->externalField;
    }

    /**
     * @return boolean
     */
    public function isForeign()
    {
        return $this->foreign;
    }

    /**
     * @return \muuska\dao\util\DAOFunction
     */
    public function getDaoFunction()
    {
        return $this->daoFunction;
    }

    /**
     * @return \muuska\dao\util\FieldParameter
     */
    public function getSubExternalField()
    {
        return $this->subExternalField;
    }

    /**
     * @param string $fieldName
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;
    }

    /**
     * @param string $externalField
     */
    public function setExternalField($externalField)
    {
        $this->externalField = $externalField;
    }

    /**
     * @param boolean $foreign
     */
    public function setForeign($foreign)
    {
        $this->foreign = $foreign;
    }

    /**
     * @param \muuska\dao\util\DAOFunction $daoFunction
     */
    public function setDaoFunction(DAOFunction $daoFunction)
    {
        $this->daoFunction = $daoFunction;
    }

    /**
     * @param \muuska\dao\util\FieldParameter $subExternalField
     */
    public function setSubExternalField(FieldParameter $subExternalField)
    {
        $this->subExternalField = $subExternalField;
    }
}
