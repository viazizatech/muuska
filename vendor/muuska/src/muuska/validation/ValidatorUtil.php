<?php
namespace muuska\validation;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\util\App;
use muuska\validation\constants\ValidationErrorCode;

class ValidatorUtil
{
	protected static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\validation\ValidatorUtil
	 */
	public static function getInstance(){
		if(self::$instance === null){
			self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @param array $fieldDefinition
	 * @param \muuska\validation\input\ValidationInput $input
	 * @param mixed $containerData
	 * @return \muuska\validation\result\LangFieldValidationResult
	 */
	public function validateLangField($fieldDefinition, \muuska\validation\input\ValidationInput $input, $containerData){
	    $result = null;
	    $values = $input->getValue();
	    $values = is_array($values) ? $values : array($values);
	    $lang = $input->getLang();
	    if($this->isFieldVisible($fieldDefinition, $containerData)){
	        if($this->isMultilingualValueEmpty($fieldDefinition, $values)){
	            if(isset($fieldDefinition['required']) && $fieldDefinition['required'] && !isset($fieldDefinition['default'])){
	                $result = App::validations()->createDefaultLangFieldValidationResult(false, array(), array(ValidationErrorCode::REQUIRED => $this->getErrorText(ValidationErrorCode::REQUIRED, $lang)));
	            }
	        }else{
	            $valid = true;
	            $allLangResults = array();
	            foreach ($values as $key => $value) {
	                if($this->isFieldValueEmpty($fieldDefinition, $value)){
	                    $allLangResults[$key] = App::validations()->createDefaultValidationResult(true);
	                }else{
	                    $allLangResults[$key] = $this->validateFieldByDefinition($fieldDefinition, App::validations()->createDefaultValidationInput($value, $lang), $containerData);
	                }
	                if(!$allLangResults[$key]->isValid()){
	                    $valid = false;
	                }
	            }
	            $result = App::validations()->createDefaultLangFieldValidationResult($valid, $allLangResults);
	        }
	    }
	    if($result === null){
	        $result = App::validations()->createDefaultLangFieldValidationResult(true, array());
	    }
	    return $result;
	}
	
    /**
     * @param array $fieldDefinition
     * @param \muuska\validation\input\ValidationInput $input
     * @param mixed $containerData
     * @return \muuska\validation\result\ValidationResult
     */
	public function validateField($fieldDefinition, \muuska\validation\input\ValidationInput $input, $containerData){
	    $result = null;
	    if($this->isFieldVisible($fieldDefinition, $containerData)){
	        if($this->isFieldValueEmpty($fieldDefinition, $input->getValue())){
	            if(isset($fieldDefinition['required']) && $fieldDefinition['required'] && !isset($fieldDefinition['default'])){
	                $result = App::validations()->createDefaultValidationResult(false, array(ValidationErrorCode::REQUIRED => $this->getErrorText(ValidationErrorCode::REQUIRED, $input->getLang())));
	            }
	        }else{
	            $result = $this->validateFieldByDefinition($fieldDefinition, $input, $containerData);
	        }
	    }
	    if($result === null){
	        $result = App::validations()->createDefaultValidationResult(true);
	    }
	    return $result;
	}
	
	/**
	 * @param array $fieldDefinition
	 * @param \muuska\validation\input\ValidationInput $input
	 * @param mixed $containerData
	 * @return \muuska\validation\result\ValidationResult
	 */
	protected function validateFieldByDefinition($fieldDefinition, \muuska\validation\input\ValidationInput $input, $containerData){
	    $value = $input->getValue();
	    $lang = $input->getLang();
	    $errors = array();
	    $valid = true;
	    $nature = isset($fieldDefinition['nature']) ? $fieldDefinition['nature'] : '';
	    $fieldValidations = $this->getFieldValidationRules($fieldDefinition);
        foreach ($fieldValidations as $validation) {
            $validationResult = App::getValidationRuleManager()->validateByRule($validation, $input);
            if(!$validationResult->isValid()){
                $valid = false;
                $errors = array_merge($errors, $validationResult->getErrors());
            }
        }
        if(isset($fieldDefinition['validator']) && ($fieldDefinition['validator'] instanceof Validator)){
            $validationResult = $fieldDefinition['validator']->validate(App::validations()->createDefaultValidationInput($containerData, $lang));
            if(!$validationResult->isValid()){
                $valid = false;
                $errors = array_merge($errors, $validationResult->getErrors());
            }
        }
        if(isset($fieldDefinition['nature']) && ($fieldDefinition['nature'] == FieldNature::OPTION) && isset($fieldDefinition['optionProvider'])){
            if(!$fieldDefinition['optionProvider']->contains($value)){
                $optionStr = implode(', ', $fieldDefinition['optionProvider']->getAllValues());
                $errors[ValidationErrorCode::OPTION] = sprintf($this->getErrorText(ValidationErrorCode::OPTION, $lang), $optionStr);
            }
        }
        if(isset($fieldDefinition['maxSize']) && (strlen($value) > $fieldDefinition['maxSize'])){
            $errors[ValidationErrorCode::MAX_SIZE] = sprintf($this->getErrorText(ValidationErrorCode::MAX_SIZE, $lang), $fieldDefinition['maxSize']);
        }
        if(isset($fieldDefinition['minSize']) && (strlen($value) < $fieldDefinition['minSize'])){
            $errors[ValidationErrorCode::MIN_SIZE] = sprintf($this->getErrorText(ValidationErrorCode::MIN_SIZE, $lang), $fieldDefinition['minSize']);
        }
        
        if(isset($fieldDefinition['maxValue']) && ($value > $fieldDefinition['maxValue'])){
            $errors[ValidationErrorCode::MAX_VALUE] = sprintf($this->getErrorText(ValidationErrorCode::MAX_VALUE, $lang), $fieldDefinition['maxValue']);
        }
        if(isset($fieldDefinition['minValue']) && ($value < $fieldDefinition['minValue'])){
            $errors[ValidationErrorCode::MIN_VALUE] = sprintf($this->getErrorText(ValidationErrorCode::MIN_VALUE, $lang), $fieldDefinition['minValue']);
        }
        if(($nature == FieldNature::IMAGE) || ($nature == FieldNature::FILE)){
            $allowedExtensions = App::getFileTools()->getAllowedExtensions($fieldDefinition);
            $excludedExtensions = isset($fieldDefinition['excludedExtensions']) ? $fieldDefinition['excludedExtensions'] : array();
            $extension = App::getFileTools()->getExtensionFromString($value);
            if (in_array($extension, $excludedExtensions)) {
                $this->getErrorText(sprintf('%s extension is not allowed', $extension), $lang);
            }
            if(!empty($allowedExtensions) && !in_array($extension, $allowedExtensions)){
                $this->getErrorText(sprintf('%s extension is not not allowed. The supported one are:'.' ', $extension, implode(', ', $allowedExtensions)), $lang);
            }
        }
	    $valid = $valid ? true : empty($errors);
	    
	    return App::validations()->createDefaultValidationResult($valid, $errors);
	}
	
	/**
	 * @param string $error
	 * @param string $lang
	 * @return string
	 */
	public function getErrorText($error, $lang){
	    return App::translateFramework(App::translations()->createValidationTranslationConfig(), $error, $lang);
	}
	
	/**
	 * @param array $fieldDefinition
	 * @param mixed $containerData
	 * @return boolean
	 */
	protected function isFieldVisible($fieldDefinition, $containerData){
	    $result = true;
	    if(isset($fieldDefinition['visibilityChecker'])){
	        $result = $fieldDefinition['visibilityChecker']->checkVisibility($containerData);
	    }
	    return $result;
	}
	
	/**
	 * @param array $fieldDefinition
	 * @param array $values
	 * @return boolean
	 */
	public function isMultilingualValueEmpty($fieldDefinition, $values)
    {
        $emptyField = true;
		foreach ($values as $value) {
		    if (!$this->isFieldValueEmpty($fieldDefinition, $value)) {
				$emptyField = false;
				break;
			}
		}
        return $emptyField;
    }
    
	/**
	 * @param array $fieldDefinition
	 * @param mixed $value
	 * @return boolean
	 */
	public function isFieldValueEmpty($fieldDefinition, $value)
    {
		$result = false;
		if($value === null){
			$result = true;
		}else{
			if(isset($fieldDefinition['type']) && ($fieldDefinition['type'] == DataType::TYPE_BOOL)){
				$result = false;
			}else{
			    $result = empty($value);
			}
		}
		return $result;
    }
	
	/**
	 * @param array $fieldDefinition
	 * @return string[]
	 */
	public function getFieldValidationRules($fieldDefinition)
    {
		$fieldValidations = array();
		if(isset($fieldDefinition['validationRules'])){
		    $fieldValidations = $fieldDefinition['validationRules'];
		}
		if(isset($fieldDefinition['validationRule'])){
		    $fieldValidations[] = $fieldDefinition['validationRule'];
		}
		if(isset($fieldDefinition['nature'])){
			$rule = $this->getValidationRuleFromNature($fieldDefinition['nature']);
			if(!empty($rule)){
				$fieldValidations[] = $rule;
			}
		}
		
		return $fieldValidations;
    }
	
	/**
	 * @param int $nature
	 * @return string
	 */
	public function getValidationRuleFromNature($nature)
    {
		$rule = '';
		$rulesByNature = array(
			FieldNature::EMAIL => 'isEmail',
			FieldNature::PERSON_NAME => 'isName',
			FieldNature::IMAGE => 'isFileName',
			FieldNature::FILE => 'isFileName',
			FieldNature::PRICE => 'isPrice',
			FieldNature::URL => 'isUrl',
			FieldNature::BIRTH_DATE => 'isBirthDate',
			FieldNature::PHONE_NUMBER => 'isPhoneNumber',
			FieldNature::PASSWORD => 'isPassword',
			FieldNature::LINK_REWRITE => 'isLinkRewrite',
			FieldNature::ADDRESS => 'isAddress',
			FieldNature::POSTAL_CODE => 'isPostCode',
			FieldNature::PERCENTAGE => 'isPercentage',
			FieldNature::LONG_TEXT => 'isGenericName',
			FieldNature::HTML => 'isCleanHtml',
			FieldNature::POSITION => 'isInt',
			FieldNature::NAME => 'isGenericName',
			FieldNature::TITLE => 'isGenericName',
			FieldNature::STATUS => 'isBool',
			FieldNature::EXISTING_MODEL_ID => 'isUnsignedId',
			FieldNature::API_KEY => 'isGenericName',
			FieldNature::OBJECT_STATE => 'isInt',
			FieldNature::USERNAME => 'isGenericName',
			FieldNature::VIRTUAL_DELETION_FIELD => 'isBool',
		);
		if(isset($rulesByNature[$nature])){
			$rule = $rulesByNature[$nature];
		}
		return $rule;
    }
}
