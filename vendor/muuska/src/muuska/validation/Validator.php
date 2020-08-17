<?php
namespace muuska\validation;

interface Validator
{
    /**
     * @param \muuska\validation\input\ValidationInput $input
     * @return \muuska\validation\result\ValidationResult
     */
    public function validate(\muuska\validation\input\ValidationInput $input) : \muuska\validation\result\ValidationResult;
}
