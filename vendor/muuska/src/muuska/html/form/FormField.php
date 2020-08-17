<?php
namespace muuska\html\form;

use muuska\html\HtmlElement;

class FormField extends HtmlElement{
    /**
     * @var string
     */
    protected $componentName = 'form_field';
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $label;
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $input;
    
    /**
     * @var bool
     */
    protected $required = false;
    
    /**
     * @var string
     */
    protected $helpText;
    
    /**
     * @var string
     */
    protected $error;
    
    /**
     * @param string $name
     * @param \muuska\html\HtmlContent $label
     * @param \muuska\html\HtmlContent $input
     */
    public function __construct($name, \muuska\html\HtmlContent $label = null, \muuska\html\HtmlContent $input = null) {
        $this->setName($name);
        if ($label !== null) {
            $this->setLabel($label);
        }
        if($input !== null){
            $this->setInput($input);
        }
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\HtmlElement::getOtherClasses()
     */
    protected function getOtherClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
        $result = parent::getOtherClasses($globalConfig, $callerConfig);
        if($this->hasError()){
            $result[] = 'has_error';
        }
        if($this->isRequired()){
            $result[] = 'required';
        }
        return $result;
    }
    
    /**
     * @return bool
     */
    public function hasError(){
        return !empty($this->error);
    }
    
    /**
     * @return bool
     */
    public function hasInput() {
        return ($this->input !== null);
    }
    
    /**
     * @return bool
     */
    public function hasHelpText() {
        return !empty($this->helpText);
    }
    
    /**
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function drawError($prefix = '', $suffix = '') {
        return $this->drawString($this->error, $prefix, $suffix);
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public function renderHelpText(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '') {
        return $this->renderString($this->helpText, $globalConfig, $callerConfig, 'helpText', $prefix, $suffix);
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
     * @return string
     */
    public function renderInput(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
        return $this->renderContent($this->input, $globalConfig, $callerConfig, 'input', '', '', $currentCallerConfig);
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param string $class
     * @return string
     */
    public function renderInputWithClass(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $class) {
        return $this->renderInput($globalConfig, $callerConfig, $this->createCallerConfig($class));
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
    
    /**
     * @return \muuska\html\HtmlContent
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return \muuska\html\HtmlElement
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return boolean
     */
    public function isRequired()
    {
        return $this->required;
    }

    /**
     * @return string
     */
    public function getHelpText()
    {
        return $this->helpText;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param \muuska\html\HtmlContent $label
     */
    public function setLabel(?\muuska\html\HtmlContent $label)
    {
        $this->label = $label;
    }

    /**
     * @param \muuska\html\HtmlContent $input
     */
    public function setInput(?\muuska\html\HtmlContent $input)
    {
        $this->input = $input;
    }

    /**
     * @param boolean $required
     */
    public function setRequired($required)
    {
        $this->required = $required;
    }

    /**
     * @param string $helpText
     */
    public function setHelpText($helpText)
    {
        $this->helpText = $helpText;
    }

    /**
     * @param string $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }
}