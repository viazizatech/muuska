<?php
namespace muuska\html\input;
class Select2 extends Select{
    /**
     * @var string
     */
    protected $componentName = 'select2';
    
    /**
     * @var string
     */
    protected $placeholder;
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\HtmlComponent::prepare()
     */
    public function prepare(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
        parent::prepare($globalConfig, $callerConfig);
        $this->setJsInitializationRequired('select2', false, 'select2'. $this->name . date('Y-m-d H:i:s'));
        if (!empty($this->placeholder)) {
            $this->addAttribute('data-placeholder', $this->placeholder);
        }
    }
    
    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param string $placeholder
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
    }
}