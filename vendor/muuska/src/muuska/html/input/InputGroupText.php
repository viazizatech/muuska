<?php
namespace muuska\html\input;

use muuska\html\HtmlElement;

class InputGroupText extends HtmlElement{
    /**
     * @var string
     */
    protected $componentName = 'input_group_text';
    
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $innerContent;
	/**
	 * @param \muuska\html\HtmlContent $innerConntent
	 */
	public function __construct(\muuska\html\HtmlContent $innerConntent = null) {
	    $this->setInnerContent($innerConntent);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::renderStatic()
	 */
	public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return $this->drawStartTag('span', $globalConfig, $callerConfig, 'input-group-text') . $this->renderContent($this->innerContent, $globalConfig, $callerConfig, 'innerContent').$this->drawEndTag('span', $globalConfig, $callerConfig);
	}
	
    /**
     * @return \muuska\html\HtmlContent
     */
    public function getInnerContent()
    {
        return $this->innerConntent;
    }

    /**
     * @param \muuska\html\HtmlContent $innerContent
     */
    public function setInnerContent(?\muuska\html\HtmlContent $innerContent)
    {
        $this->innerContent = $innerContent;
    }
}