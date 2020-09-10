<?php
namespace muuska\html\input;
class Select extends Option{
    /**
     * @var string
     */
    protected $componentName = 'select';
    
    /**
     * @var bool
     */
    protected $emptyOptionEnabled;
    
    /**
     * @var string
     */
    protected $emptyOptionText;
    
    /**
     * @var string
     */
    protected $emptyOptionValue;
    
    /**
     * @var bool
     */
    protected $multiple;
    
    /**
     * @param string $name
     * @param \muuska\option\provider\OptionProvider $optionProvider
     * @param mixed $value
     * @param boolean $emptyOptionEnabled
     * @param string $emptyOptionValue
     * @param string $emptyOptionText
     */
    public function __construct($name, \muuska\option\provider\OptionProvider $optionProvider = null, $value = null, $emptyOptionEnabled = false, $emptyOptionValue = null, $emptyOptionText = null) {
        parent::__construct($name, $optionProvider, $value);
        $this->setEmptyOptionEnabled($emptyOptionEnabled);
        $this->setEmptyOptionValue($emptyOptionValue);
        $this->setEmptyOptionText($emptyOptionText);
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     */
    public function renderInnerContent(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig) {
        $html = '';
        $options = $this->getOptions();
        if($this->emptyOptionEnabled){
            $html .= '<option value="'.$this->emptyOptionValue.'">'.$this->emptyOptionText.'</option>';
        }
        foreach ($options as $option) {
            $html .= '<option value="'.$option->getValue().'"'.$this->getStringFromCondition($this->isOptionSelected($option), 'selected="selected"', '', true).'>'.$option->getLabel().'</option>';
        }
        return $html;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\input\Option::isOptionSelected()
     */
    public function isOptionSelected(\muuska\option\Option $option){
        $result = false;
        if($this->value !== null){
            if($this->multiple){
                $result = (is_array($this->value) && in_array($option->getValue(), $this->value));
            }else{
                $result = is_array($this->value) ? in_array($option->getValue(), $this->value) : ($option->getValue() == $this->value);
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\HtmlComponent::renderStatic()
     */
    public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
        return $this->drawStartTag('select', $globalConfig, $callerConfig) . $this->renderInnerContent($globalConfig, $callerConfig).$this->drawEndTag('select', $globalConfig, $callerConfig);
    }
	
    /**
     * {@inheritDoc}
     * @see \muuska\html\HtmlElement::getOtherAttributes()
     */
    protected function getOtherAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $attributes = parent::getOtherAttributes($globalConfig, $callerConfig);
	    if(!empty($this->name)){
	        $attributes['name'] = $this->name;
	    }
	    if($this->multiple){
	        $attributes['multiple'] = 'multiple';
	    }
	    return $attributes;
	}
	
    /**
     * @return boolean
     */
    public function isEmptyOptionEnabled()
    {
        return $this->emptyOptionEnabled;
    }

    /**
     * @return string
     */
    public function getEmptyOptionText()
    {
        return $this->emptyOptionText;
    }

    /**
     * @return string
     */
    public function getEmptyOptionValue()
    {
        return $this->emptyOptionValue;
    }

    /**
     * @param boolean $emptyOptionEnabled
     */
    public function setEmptyOptionEnabled($emptyOptionEnabled)
    {
        $this->emptyOptionEnabled = $emptyOptionEnabled;
    }

    /**
     * @param string $emptyOptionText
     */
    public function setEmptyOptionText($emptyOptionText)
    {
        $this->emptyOptionText = $emptyOptionText;
    }

    /**
     * @param string $emptyOptionValue
     */
    public function setEmptyOptionValue($emptyOptionValue)
    {
        $this->emptyOptionValue = $emptyOptionValue;
    }
    /**
     * @return boolean
     */
    public function isMultiple()
    {
        return $this->multiple;
    }

    /**
     * @param boolean $multiple
     */
    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;
    }
}