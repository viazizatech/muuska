<?php
namespace muuska\validation\input;

class DefaultValidationInput implements ValidationInput
{
    /**
     * @var string
     */
    protected $lang;
    
    /**
     * @var mixed
     */
    protected $value;
    
    /**
     * @param mixed $value
     * @param string $lang
     */
    public function __construct($value, $lang) {
        $this->value = $value;
        $this->lang = $lang;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\validation\input\ValidationInput::getLang()
     */
    public function getLang()
    {
        return $this->lang;
    }
    
    /**
     * @return mixed
     */
    public function getValue(){
        return $this->value;
    }
}
