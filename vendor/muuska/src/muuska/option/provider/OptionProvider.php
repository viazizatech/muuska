<?php
namespace muuska\option\provider;

interface OptionProvider
{
    /**
     * @return \muuska\option\Option[]
     */
    public function getOptions();
    
    /**
     * @param mixed $value
     * @return string
     */
    public function getLabelFromValue($value);
    
    /**
     * @param mixed $value
     * @return \muuska\option\Option
     */
    public function getOptionFromValue($value);
    
    /**
     * @param mixed $value
     * @return bool
     */
    public function contains($value);
    
    /**
     * @return array
     */
    public function getAllValues();
}

