<?php
namespace muuska\validation\result;

class DefaultValidationResult implements ValidationResult{
    
    /**
     * @var bool
     */
    protected $valid;
    
    /**
     * @var string[]
     */
    protected $errors;
    
    /**
     * @var string
     */
    protected $errorMessage;
    
    /**
     * @param bool $valid
     * @param string[] $errors
     * @param string $errorMessage
     */
    public function __construct($valid, $errors = array(), $errorMessage = null) {
        $this->valid = $valid;
        $this->errors = $errors;
        $this->errorMessage = $errorMessage;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\validation\result\ValidationResult::isValid()
     */
    public function isValid(){
        return $this->valid;
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
     * @see \muuska\validation\result\ValidationResult::getErrorMessage()
     */
    public function getErrorMessage(){
        return empty($this->errorMessage) ? implode(', ', $this->errors) : $this->errorMessage;
    }
}