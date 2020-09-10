<?php
namespace muuska\html\input;

use muuska\html\AbstractChildWrapper;

class CustomInputGroup extends AbstractChildWrapper{
    /**
     * @var string
     */
    protected $componentName = 'custom_input_group';
	
    /**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::renderStatic()
	 */
	public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return $this->drawStartTag('div', $globalConfig, $callerConfig, 'input-group') . $this->generateChildren($globalConfig, $callerConfig).$this->drawEndTag('div', $globalConfig, $callerConfig);
	}
}