<?php
namespace muuska\html;
class HtmlFieldValue extends HtmlElement{
    /**
     * @var string
     */
    protected $componentName = 'field_value';
    
    /**
	 * @var mixed
	 */
	protected $value;
	
	/**
	 * @var string
	 */
	protected $htmlValue;
	
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $label;
	
	/**
	 * @var \muuska\renderer\value\ValueRenderer
	 */
	protected $valueRenderer;
	
	/**
	 * @param string $name
	 * @param mixed $value
	 * @param string $htmlValue
	 * @param \muuska\html\HtmlContent $label
	 * @param \muuska\renderer\value\ValueRenderer $valueRenderer
	 */
	public function __construct($name, $value, $htmlValue = null, \muuska\html\HtmlContent $label = null, \muuska\renderer\value\ValueRenderer $valueRenderer = null) {
	    $this->setName($name);
	    $this->setLabel($label);
	    $this->setValue($value);
	    $this->setValueRenderer($valueRenderer);
	    $this->setHtmlValue($htmlValue);
	}
	
	/**
	 * @return boolean
	 */
	public function hasValueRenderer(){
	    return ($this->valueRenderer !== null);
	}
	
	/**
	 * @return boolean
	 */
	public function hasHtmlValue(){
	    return ($this->htmlValue !== null);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderValue(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    $result = '';
	    if($this->hasValueRenderer()){
	        $result = $this->valueRenderer->renderValue($this->value, $globalConfig, $currentCallerConfig);
	    }elseif ($this->hasHtmlValue()){
	        $result = $this->htmlValue;
	    }else{
	        $result = $this->value;
	    }
	    return $result;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $class
	 * @return string
	 */
	public function renderLabelWithClass(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $class) {
	    return $this->renderLabel($globalConfig, $callerConfig, $this->createCallerConfig($class));
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderLabel(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContent($this->label, $globalConfig, $callerConfig, 'label', '', '', $currentCallerConfig);
	}
	
	public function hasLabel(){
	    return ($this->label !== null);
	}
	
    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getHtmlValue()
    {
        return $this->htmlValue;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return \muuska\renderer\value\ValueRenderer
     */
    public function getValueRenderer()
    {
        return $this->valueRenderer;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @param string $htmlValue
     */
    public function setHtmlValue($htmlValue)
    {
        $this->htmlValue = $htmlValue;
    }

    /**
     * @param \muuska\html\HtmlContent $label
     */
    public function setLabel(?\muuska\html\HtmlContent $label)
    {
        $this->label = $label;
    }

    /**
     * @param \muuska\renderer\value\ValueRenderer $valueRenderer
     */
    public function setValueRenderer(?\muuska\renderer\value\ValueRenderer $valueRenderer)
    {
        $this->valueRenderer = $valueRenderer;
    }
}