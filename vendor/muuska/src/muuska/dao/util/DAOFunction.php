<?php
namespace muuska\dao\util;

use muuska\dao\constants\DAOValueType;
use muuska\util\App;

class DAOFunction
{
	/**
	 * @var string
	 */
	protected $code;
	
    /**
     * @var \muuska\dao\util\DAOFunctionParameter[]
     */
    protected $parameters = array();
	
	/**
	 * @param string $code
	 */
	public function __construct($code) {
		$this->setCode($code);
	}
	
	/**
	 * @param \muuska\dao\util\DAOFunctionParameter $functionParameter
	 */
	public function addParameter(\muuska\dao\util\DAOFunctionParameter $functionParameter) {
		$this->parameters[] = $functionParameter;
	}
	
	/**
	 * @param \muuska\dao\util\DAOFunctionParameter[] $functionParameters
	 */
	public function addParameters($functionParameters) {
	    if(is_array($functionParameters)){
	        foreach ($functionParameters as $functionParameter) {
	            $this->addParameter($functionParameter);
	        }
	    }
	}
	
	/**
	 * @param mixed $value
	 * @return \muuska\dao\util\DAOFunctionParameter
	 */
	public function addSimpleParameter($value) {
		$parameter = App::daos()->createDAOFunctionParameter($value);
		$this->addParameter($parameter);
		return $parameter;
	}
	
	/**
	 * @param string $fieldName
	 * @param boolean $foreign
	 * @param string $externalField
	 * @return \muuska\dao\util\DAOFunctionParameter
	 */
	public function addFieldParameter($fieldName, $foreign = false, $externalField = null) {
	    $fieldParameter = App::daos()->createFieldParameter($fieldName, $foreign, $externalField);
		$parameter = App::daos()->createDAOFunctionParameter($fieldParameter, DAOValueType::FIELD_PARAMETER);
		$this->addParameter($parameter);
		return $parameter;
	}
	
	/**
	 * @param string $code
	 * @return \muuska\dao\util\DAOFunctionParameter
	 */
	public function addDaoFunctionParameter($code) {
	    $daoFunction = App::daos()->createDAOFunction($code);
	    $parameter = App::daos()->createDAOFunctionParameter($daoFunction, DAOValueType::DAO_FUNCTION);
		$this->addParameter($parameter);
		return $parameter;
	}
	
    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return \muuska\dao\util\DAOFunctionParameter[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @param \muuska\dao\util\DAOFunctionParameter[] $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = array();
        $this->addParameters($parameters);
    }
}
