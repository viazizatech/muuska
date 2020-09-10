<?php
namespace muuska\validation\result;

interface ModelValidationResult extends ValidationResult{
    /**
     * @return ValidationResult[]
     */
    public function getFieldResults();
    
    /**
     * @param string $field
     * @return ValidationResult
     */
    public function getFieldResult($field);
    
    /**
     * @return LangFieldValidationResult[]
     */
    public function getLangFieldValidationResults();
    
    /**
     * @param string $field
     * @return LangFieldValidationResult
     */
    public function getLangFieldValidationResult($field);
    
    /**
     * @param string $field
     * @return ModelValidationResult
     */
    public function getAssociatedModelResult($field);
    
    /**
     * @param string $associationName
     * @return ModelValidationResult[]
     */
    public function getMultipleAssociatedModelResults($associationName);
}