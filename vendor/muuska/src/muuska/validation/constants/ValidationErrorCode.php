<?php
namespace muuska\validation\constants;

class ValidationErrorCode{
	const REQUIRED = 'required';
    const UNIQUE = 'unique';
    const MULTIPLE_UNIQUE = 'multipleUnique';
    const MAX_SIZE = 'maxSize';
    const MIN_SIZE = 'minSize';
    const MAX_VALUE = 'maxValue';
    const MIN_VALUE = 'minValue';
    const OPTION = 'option';
}