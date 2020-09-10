<?php
namespace muuska\html\input;

use muuska\html\HtmlElement;

abstract class AbstractHtmlInput extends HtmlElement{
	/**
	 * @var mixed
	 */
	protected $value;
	
	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function __construct($name, $value = null) {
	    $this->setName($name);
	    $this->setValue($value);
	}
        
    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}