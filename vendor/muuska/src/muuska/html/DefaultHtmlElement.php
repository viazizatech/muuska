<?php
namespace muuska\html;
class DefaultHtmlElement extends HtmlElement{
    /**
     * @var string
     */
    protected $tag;
    
    /**
     * @var HtmlContent
     */
    protected $innerContent;
    
    /**
     * @param string $tag
     * @param HtmlContent $innerContent
     * @param string $name
     * @param string $componentName
     * @param \muuska\renderer\HtmlContentRenderer $renderer
     */
    public function __construct($tag, HtmlContent $innerContent = null, $name = null, $componentName = null, \muuska\renderer\HtmlContentRenderer $renderer = null) {
        parent::__construct($name, $componentName, $renderer);
        $this->setTag($tag);
        $this->setInnerContent($innerContent);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\HtmlComponent::renderStatic()
     */
    public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
        return $this->drawStartTag($this->tag, $globalConfig, $callerConfig) . $this->renderContent($this->innerContent, $globalConfig, $callerConfig, 'innerContent').$this->drawEndTag($this->tag, $globalConfig, $callerConfig);
    }
    
    /**
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * @param string $tag
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
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