<?php
namespace muuska\html;
class HtmlDiv extends HtmlElement{
    /**
	 * @var HtmlContent
	 */
	protected $innerContent;
	
	/**
	 * @param string $tag
	 * @param HtmlContent $innerContent
	 * @param string $name
	 * @param \muuska\renderer\HtmlContentRenderer $renderer
	 */
	public function __construct(HtmlContent $innerContent = null, $name = null, \muuska\renderer\HtmlContentRenderer $renderer = null) {
	    parent::__construct($name, 'div', $renderer);
	    $this->setInnerContent($innerContent);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::renderStatic()
	 */
	public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return $this->drawStartTag('div', $globalConfig, $callerConfig) . $this->renderContent($this->innerContent, $globalConfig, $callerConfig, 'innerContent').$this->drawEndTag('div', $globalConfig, $callerConfig);
	}
	
	/**
	 * @return \muuska\html\HtmlContent
	 */
	public function getInnerContent()
	{
	    return $this->innerContent;
	}
	
	/**
	 * @param \muuska\html\HtmlContent $innerContent
	 */
	public function setInnerContent(?HtmlContent $innerContent)
	{
	    $this->innerContent = $innerContent;
	}
}