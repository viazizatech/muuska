<?php
namespace muuska\html\command;
use muuska\html\AbstractChildWrapper;

class ButtonGroup extends AbstractChildWrapper{
    /**
     * @var string
     */
    protected $componentName = 'button_group';
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::renderStatic()
	 */
	public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return $this->drawStartTag('div', $globalConfig, $callerConfig, 'btn-group') . $this->generateChildren($globalConfig, $callerConfig).$this->drawEndTag('div', $globalConfig, $callerConfig);
	}
}