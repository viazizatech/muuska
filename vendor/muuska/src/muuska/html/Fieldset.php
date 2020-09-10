<?php
namespace muuska\html;

class Fieldset extends ChildrenContainer{
    /**
     * @var string
     */
    protected $componentName = 'fieldset';
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\HtmlComponent::renderStatic()
     */
    public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
        return $this->drawStartTag('fieldset', $globalConfig, $callerConfig) .$this->renderString($this->label, $globalConfig, $callerConfig, 'label', '<legend>', '</legend>') . $this->generateChildren($globalConfig, $callerConfig).$this->drawEndTag('fieldset', $globalConfig, $callerConfig);
    }
}