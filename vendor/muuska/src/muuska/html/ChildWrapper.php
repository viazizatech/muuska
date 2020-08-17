<?php
namespace muuska\html;

class ChildWrapper extends AbstractChildWrapper{
    /**
     * @var \muuska\html\HtmlContent[]
     */
    protected $children = array();
    
    /**
     * @var string
     */
    protected $tag;
    
    /**
     * @param string $tag
     * @param HtmlContent[] $children
     */
    public function __construct($tag, $children = array()) {
        $this->setTag($tag);
        $this->setChildren($children);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\HtmlComponent::renderStatic()
     */
    public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
        return $this->drawStartTag($this->tag, $globalConfig, $callerConfig) . $this->generateChildren($globalConfig, $callerConfig).$this->drawEndTag($this->tag, $globalConfig, $callerConfig);
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