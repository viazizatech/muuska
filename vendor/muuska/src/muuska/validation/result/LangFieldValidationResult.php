<?php
namespace muuska\validation\result;

interface LangFieldValidationResult extends ValidationResult{
    
    /**
     * @param string $lang
     * @return ValidationResult
     */
    public function getLangResult($lang);
    
    /**
     * @return ValidationResult[]
     */
    public function getAllLangResults();
}