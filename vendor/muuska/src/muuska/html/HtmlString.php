<?php
namespace muuska\html;
class HtmlString implements HtmlContent{
    /**
     * @var string
     */
    protected $html;
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @param string $html
     * @param string $name
     */
    public function __construct($html, $name = null) {
	    $this->setHtml($html);
	    $this->setName($name);
	}
	
	/**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param string $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\ContentCreator::getName()
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     */
    public function setName($name){
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\html\ContentCreator::createContent()
     */
    public function createContent()
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\html\HtmlContent::generate()
     */
    public function generate(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null)
    {
        return (($callerConfig !== null) && $callerConfig->hasRenderer()) ? $callerConfig->getRenderer()->renderHtml($this, $globalConfig, $callerConfig) : $this->html;
    }
}