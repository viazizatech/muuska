<?php
namespace muuska\option;
class AssociativeArrayOption implements Option{
    /**
     * @var array
     */
    protected $array;
    
    /**
     * @param array $array
     */
    public function __construct($array) {
        $this->array = $array;
    }
    
    /**
     * @param string $key
     * @return mixed
     */
    public function getFromKey($key){
        return isset($this->array[$key]) ? $this->array[$key] : null;
    }
    
	/**
	 * {@inheritDoc}
	 * @see \muuska\option\Option::getValue()
	 */
	public function getValue(){
	    return $this->getFromKey('value');
    }
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\option\Option::getLabel()
	 */
	public function getLabel(){
	    return $this->getFromKey('label');
    }
    
    /**
     * @return string
     */
    public function __toString() {
        return $this->getLabel();
    }
}