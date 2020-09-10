<?php
namespace muuska\controller\param;

class DefaultControllerParam implements ControllerParam
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $value;
    
    /**
     * @var object
     */
    protected $object;
    
    /**
     * @param string $name
     * @param string $value
     * @param object $object
     */
    public function __construct($name, $value, object $object = null){
        $this->name = $name;
        $this->value = $value;
        $this->object = $object;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParam::getName()
     */
    public function getName(){
        return $this->name;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParam::getValue()
     */
    public function getValue(){
        return $this->value;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParam::getObject()
     */
    public function getObject(){
        return $this->object;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\controller\param\ControllerParam::hasObject()
     */
    public function hasObject(){
        return ($this->object !== null);
    }
}