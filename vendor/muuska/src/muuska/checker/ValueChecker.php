<?php
namespace muuska\checker;
use muuska\util\App;

class ValueChecker implements Checker{
    /**
     * @var mixed
     */
    protected $expectedValue;
    
    /**
     * @var \muuska\getter\Getter
     */
    protected $valueGetter;
    
    /**
     * @var bool
     */
    protected $strict;
    
    /**
     * @var int
     */
    protected $operator;
    
    /**
     * @param \muuska\getter\Getter $valueGetter
     * @param mixed $expectedValue
     * @param boolean $strict
     * @param int $operator
     */
    public function __construct(\muuska\getter\Getter $valueGetter, $expectedValue, $operator = null, $strict = false){
        $this->valueGetter = $valueGetter;
        $this->expectedValue = $expectedValue;
        $this->operator = $operator;
        $this->strict = $strict;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\checker\Checker::check()
     */
    public function check($data)
    {
        return App::getTools()->checkValue($this->valueGetter->get($data), $this->expectedValue, $this->operator, $this->strict);
    }
}