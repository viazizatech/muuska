<?php
namespace muuska\instantiator;

class Validations
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Validations
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @return \muuska\validation\ValidatorUtil
	 */
	public function getValidatorUtilInstance():\muuska\validation\ValidatorUtil{
	    return \muuska\validation\ValidatorUtil::getInstance();
	}
	
	/**
	 * @return \muuska\validation\ValidationRuleManager
	 */
	public function getValidationRuleManagerInstance():\muuska\validation\ValidationRuleManager{
		return \muuska\validation\ValidationRuleManager::getInstance();
	}
	
	/**
	 * @param object $model
	 * @param string $lang
	 * @param \muuska\dao\DAO $dao
	 * @param \muuska\dao\util\SaveConfig $saveConfig
	 * @param boolean $update
	 * @param \muuska\validation\input\ModelValidationInput $parentInput
	 * @return \muuska\validation\input\ModelValidationInput
	 */
	public function createModelValidationInput(object $model, $lang, \muuska\dao\DAO $dao = null, \muuska\dao\util\SaveConfig $saveConfig = null, $update = false, \muuska\validation\input\ModelValidationInput $parentInput = null){
	    return new \muuska\validation\input\ModelValidationInput($model, $lang, $dao, $saveConfig, $update, $parentInput);
	}
	
	/**
	 * @param mixed $value
	 * @param string $lang
	 * @return \muuska\validation\input\DefaultValidationInput
	 */
	public function createDefaultValidationInput($value, $lang){
		return new \muuska\validation\input\DefaultValidationInput($value, $lang);
	}
	
	/**
	 * @param bool $valid
     * @param string[] $errors
     * @param string $errorMessage
	 * @return \muuska\validation\result\DefaultValidationResult
	 */
	public function createDefaultValidationResult($valid, $errors = array(), $errorMessage = null){
	    return new \muuska\validation\result\DefaultValidationResult($valid, $errors, $errorMessage);
	}
	
	/**
	 * @param bool $valid
     * @param \muuska\validation\result\ValidationResult[] $fieldResults
     * @param string[] $errors
     * @param string $errorMessage
     * @param \muuska\validation\result\LangFieldValidationResult[] $langFieldValidationResults
     * @param \muuska\validation\result\ModelValidationResult[] $associatedModelResults
     * @param \muuska\validation\result\ModelValidationResult[][] $multipleAssociatedModelsResults
	 * @return \muuska\validation\result\DefaultModelValidationResult
	 */
	public function createDefaultModelValidationResult($valid, $fieldResults, $errors = array(), $errorMessage = null, $langFieldValidationResults = array(), $associatedModelResults = array(), $multipleAssociatedModelsResults = array()){
	    return new \muuska\validation\result\DefaultModelValidationResult($valid, $fieldResults, $errors, $errorMessage, $langFieldValidationResults, $associatedModelResults, $multipleAssociatedModelsResults);
	}
	
	/**
	 * @param bool $valid
     * @param \muuska\validation\result\ValidationResult[] $allLangResults
     * @param string[] $errors
     * @param string $errorMessage
	 * @return \muuska\validation\result\DefaultLangFieldValidationResult
	 */
	public function createDefaultLangFieldValidationResult($valid, $allLangResults, $errors = array(), $errorMessage = null){
	    return new \muuska\validation\result\DefaultLangFieldValidationResult($valid, $allLangResults, $errors, $errorMessage);
	}
	
	/**
	 * @param callable $callback
	 * @param array $initialParams
	 * @return \muuska\validation\DefaultValidator
	 */
	public function createDefaultValidator($callback, $initialParams = null){
	    return new \muuska\validation\DefaultValidator($callback, $initialParams);
	}
}
