<?php
namespace muuska\getter;

class StaticGetter implements Getter{
    /**
     * @var mixed
     */
    protected $value;
    
    /**
     * @param mixed $value
     */
    public function __construct($value) {
        $this->value = $value;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\getter\Getter::get()
     */
    public function get($data)
    {
        return $this->value;
    }
}