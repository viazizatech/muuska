<?php
namespace muuska\html\input;
class Checkbox extends AbstractHtmlInput{
    /**
     * @var string
     */
    protected $componentName = 'checkbox';
    
	/**
	 * @var string
	 */
	protected $label;
	
	/**
	 * @var bool
	 */
	protected $checked;
	
	/**
	 * @param string $name
	 * @param string $label
	 * @param mixed $value
	 * @param boolean $checked
	 */
	public function __construct($name, $label = '', $value = null, $checked = false) {
	    parent::__construct($name, $value);
	    $this->setlabel($label);
	    $this->setChecked($checked);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::renderStatic()
	 */
	public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return '<input' . $this->drawAllAttributes($globalConfig, $callerConfig) . ' name="'.$this->getName().'" value="'.$this->getValue().'"'.$this->getStringFromCondition($this->isChecked(), 'checked', '', true).' />';
	}
	
    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return boolean
     */
    public function isChecked()
    {
        return $this->checked;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @param boolean $checked
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;
    }
}