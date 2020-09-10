<?php
namespace muuska\html;
class SelfClosingElement extends HtmlElement{
    /**
     * @var string
     */
    protected $tag;
    
    /**
     * @param string $tag
     * @param string $name
     * @param string $componentName
     * @param \muuska\renderer\HtmlContentRenderer $renderer
     */
    public function __construct($tag, $name = null, $componentName = null, \muuska\renderer\HtmlContentRenderer $renderer = null) {
        parent::__construct($name, $componentName, $renderer);
        $this->setTag($tag);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\HtmlComponent::renderStatic()
     */
    public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
        return '<'.$this->tag.$this->drawAllAttributes($globalConfig, $callerConfig).' />';
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
}