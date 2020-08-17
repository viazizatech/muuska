<?php
namespace muuska\validation\input;

interface ValidationInput
{
    /**
     * @return string
     */
    public function getLang();
    
    /**
     * @return mixed
     */
    public function getValue();
}
