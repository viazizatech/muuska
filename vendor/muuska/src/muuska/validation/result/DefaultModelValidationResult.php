<?php
namespace muuska\validation\result;

class DefaultModelValidationResult implements ModelValidationResult{
    /**
     * @var bool
     */
    protected $valid;
    
    /**
     * @var ValidationResult[]
     */
    protected $fieldResults;
    
    /**
     * @var ModelValidationResult[]
     */
    protected $associatedModelResults;
    
    /**
     * @var ModelValidationResult[][]
     */
    protected $multipleAssociatedModelsResults;
    
    /**
     * @var string[]
     */
    protected $errors;
    
    /**
     * @var string
     */
    protected $errorMessage;
    
    /**
     * @var LangFieldValidationResult[]
     */
    protected $langFieldValidationResults;
    
    /**
     * @param bool $valid
     * @param ValidationResult[] $fieldResults
     * @param string[] $errors
     * @param string $errorMessage
     * @param LangFieldValidationResult[] $langFieldValidationResults
     * @param ModelValidationResult[] $associatedModelResults
     * @param ModelValidationResult[][] $multipleAssociatedModelsResults
     */
    public function __construct($valid, $fieldResults, $errors = array(), $errorMessage = null, $langFieldValidationResults = array(), $associatedModelResults = array(), $multipleAssociatedModelsResults = array()) {
        $this->valid = $valid;
        $this->fieldResults = $fieldResults;
        $this->associatedModelResults = $associatedModelResults;
        $this->multipleAssociatedModelsResults = $multipleAssociatedModelsResults;
        $this->errors = $errors;
        $this->errorMessage = $errorMessage;
        $this->langFieldValidationResults = $langFieldValidationResults;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\validation\result\ValidationResult::isValid()
     */
    public function isValid(){
        return $this->valid;
    }
    
    /**
     * @return bool
     */
    public function hasErrors(){
        return !empty($this->errors);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\validation\result\ValidationResult::getErrors()
     */
    public function getErrors(){
        return $this->errors;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\validation\result\ModelValidationResult::getFieldResults()
     */
    public function getFieldResults(){
        return $this->fieldResults;
    }
    
    /**
     * @return ModelValidationResult[]
     */
    public function getAssociatedModelResults(){
        return $this->associatedModelResults;
    }
    
    /**
     * @return ModelValidationResult[][]
     */
    public function getMultipleAssociatedModelsResults(){
        return $this->multipleAssociatedModelsResults;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\validation\result\ModelValidationResult::getFieldResult()
     */
    public function getFieldResult($field)
    {
        return isset($this->fieldResults[$field]) ? $this->fieldResults[$field] : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\validation\result\ModelValidationResult::getMultipleAssociatedModelResults($associationName)
     */
    public function getMultipleAssociatedModelResults($associationName)
    {
        return isset($this->multipleAssociatedModelsResults[$associationName]) ? $this->multipleAssociatedModelsResults[$associationName] : array();
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\validation\result\ModelValidationResult::getAssociatedModelResult()
     */
    public function getAssociatedModelResult($field)
    {
        return isset($this->associatedModelResults[$field]) ? $this->associatedModelResults[$field] : null;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\validation\result\ValidationResult::getErrorMessage()
     */
    public function getErrorMessage()
    {
        return empty($this->errorMessage) ? implode(', ', $this->errors) : $this->errorMessage;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\validation\result\ModelValidationResult::getLangFieldValidationResults()
     */
    public function getLangFieldValidationResults(){
        return $this->langFieldValidationResults;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\validation\result\ModelValidationResult::getLangFieldValidationResult($field)
     */
    public function getLangFieldValidationResult($field){
        return isset($this->langFieldValidationResults[$field]) ? $this->langFieldValidationResults[$field] : null;
    }
}