<?php
namespace muuska\dao\exception;

class InvalidModelException extends \Exception{
    /**
     * @var \muuska\validation\result\ModelValidationResult
     */
    protected $modelValidationResult;
    
    /**
     * @param \muuska\validation\result\ModelValidationResult $modelValidationResult
     * @param mixed $message
     * @param mixed $code
     * @param mixed $previous
     */
    public function __construct(\muuska\validation\result\ModelValidationResult $modelValidationResult, $message = null, $code = null, $previous = null){
        parent::__construct($message, $code, $previous);
        $this->modelValidationResult = $modelValidationResult;
    }
    
    /**
     * @return \muuska\validation\result\ModelValidationResult
     */
    public function getModelValidationResult() {
        return $this->modelValidationResult;
    }
}