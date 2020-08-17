<?php
namespace muuska\html\input;


class HtmlInput extends AbstractHtmlInput{
    /**
     * @var string
     */
    protected $componentName = 'default_input';
    
	/**
	 * @var string
	 */
	protected $type;
	
	/**
	 * @var string
	 */
	protected $placeholder;
	
	/**
	 * @param string $type
	 * @param string $name
	 * @param mixed $value
	 * @param string $placeholder
	 */
	public function __construct($type, $name, $value = null, $placeholder = null) {
	    parent::__construct($name, $value);
	    $this->setType($type);
	    $this->setPlaceholder($placeholder);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlElement::getOtherAttributes()
	 */
	protected function getOtherAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $attributes = parent::getOtherAttributes($globalConfig, $callerConfig);
	    $attributes['type'] = $this->type;
	    if(!empty($this->name)){
	        $attributes['name'] = $this->name;
	    }
	    if(!empty($this->placeholder)){
	        $attributes['placeholder'] = $this->placeholder;
	    }
	    $attributes['value'] = $this->value;
	    return $attributes;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::renderStatic()
	 */
	public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return '<input' . $this->drawAllAttributes($globalConfig, $callerConfig) . ' />';
	}
	
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param string $placeholder
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
    }
}