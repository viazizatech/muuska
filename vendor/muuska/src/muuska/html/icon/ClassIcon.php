<?php
namespace muuska\html\icon;

use muuska\html\HtmlElement;

class ClassIcon extends HtmlElement{
    /**
     * @var string
     */
    protected $componentName = 'class_icon';
    
	/**
	 * @var string
	 */
	protected $value;
	
	/**
	 * @param string $value
	 * @param string $type
	 */
	public function __construct($value) {
		$this->setValue($value);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlElement::getOtherClasses()
	 */
	protected function getOtherClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $result = parent::getOtherClasses($globalConfig, $callerConfig);
	    if(!empty($this->value)){
	        $result[] = $this->value;
	    }
	    return $result;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::renderStatic()
	 */
	public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return $this->drawStartTag('i', $globalConfig, $callerConfig) .$this->drawEndTag('i', $globalConfig, $callerConfig);
	}
	
    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}