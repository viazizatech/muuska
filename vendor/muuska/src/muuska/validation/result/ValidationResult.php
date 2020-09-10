<?php
namespace muuska\validation\result;

interface ValidationResult{
    
    /**
     * @return bool
     */
    public function isValid();
    
    /**
     * @return string[]
     */
    public function getErrors();
    
    /**
     * @return string
     */
    public function getErrorMessage();
}