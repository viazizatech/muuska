<?php
namespace muuska\html\command;
class HtmlLink extends HtmlCommand{
    /**
     * @var string
     */
    protected $componentName = 'link';
    
	/**
	 * @var string
	 */
	protected $href;
	
	/**
	 * @var bool
	 */
	protected $buttonStyleEnabled = false;
	
	/**
	 * @var string
	 */
	protected $title;
	
	/**
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param string $href
	 * @param \muuska\html\HtmlContent $icon
	 * @param string $title
	 * @param boolean $buttonStyleEnabled
	 * @param string $style
	 */
	public function __construct(\muuska\html\HtmlContent $innerContent = null, $href ='#', \muuska\html\HtmlContent $icon = null, $title = '', $buttonStyleEnabled = false, $style = null) {
	    parent::__construct($innerContent, $icon, $style);
		$this->setHref($href);
		$this->setButtonStyleEnabled($buttonStyleEnabled);
		$this->setTitle($title);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\command\HtmlCommand::getCommandClasses()
	 */
	public function getCommandClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	   return $this->buttonStyleEnabled ? parent::getCommandClasses($globalConfig, $callerConfig) : array();
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\command\HtmlCommand::getOtherAttributes()
	 */
	protected function getOtherAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $attributes = parent::getOtherAttributes($globalConfig, $callerConfig);
	    if(!empty($this->href)){
	        $attributes['href'] = $this->href;
	    }
	    if(!empty($this->title)){
	        $attributes['title'] = $this->title;
	    }
	    return $attributes;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::renderStatic()
	 */
	public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return $this->drawStartTag('a', $globalConfig, $callerConfig) . $this->renderInner($globalConfig, $callerConfig).$this->drawEndTag('a', $globalConfig, $callerConfig);
	}
	
    /**
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * @return boolean
     */
    public function isButtonStyleEnabled()
    {
        return $this->buttonStyleEnabled;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $href
     */
    public function setHref($href)
    {
        $this->href = $href;
    }

    /**
     * @param boolean $buttonStyleEnabled
     */
    public function setButtonStyleEnabled($buttonStyleEnabled)
    {
        $this->buttonStyleEnabled = $buttonStyleEnabled;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}