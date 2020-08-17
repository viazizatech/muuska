<?php
namespace muuska\validation;
use muuska\util\FunctionCallback;

class DefaultValidator extends FunctionCallback implements Validator{
    /**
     * {@inheritDoc}
     * @see \muuska\validation\Validator::validate()
     */
    public function validate(\muuska\validation\input\ValidationInput $input): \muuska\validation\result\ValidationResult
    {
        $result = null;
        if($this->callback !== null){
            if(empty($this->initialParams)){
                $result = call_user_func($this->callback, $input);
            }else{
                $result = call_user_func($this->callback, $this->initialParams, $input);
            }
        }
        return $result;
    }
}