<?php
namespace muuska\validation\result;

class DefaultLangFieldValidationResult extends DefaultValidationResult implements LangFieldValidationResult{
    /**
     * @var ValidationResult[]
     */
    protected $allLangResults;
    
    /**
     * @param bool $valid
     * @param ValidationResult[] $allLangResults
     * @param string[] $errors
     * @param string $errorMessage
     */
    public function __construct($valid, $allLangResults, $errors = array(), $errorMessage = null) {
        parent::__construct($valid, $errors, $errorMessage);
        $this->allLangResults = $allLangResults;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\validation\result\LangFieldValidationResult::getLangResult()
     */
    public function getLangResult($lang)
    {
        return isset($this->allLangResults[$lang]) ? $this->allLangResults[$lang] : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\validation\result\LangFieldValidationResult::getAllLangResults()
     */
    public function getAllLangResults()
    {
        return $this->allLangResults;
    }
}