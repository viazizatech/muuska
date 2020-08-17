<?php
namespace muuska\html\command;
class Button extends HtmlCommand{
    /**
     * @var string
     */
    protected $componentName = 'button';
    
	/**
	 * @var string
	 */
    protected $type = 'button';
    
    /**
     * @param \muuska\html\HtmlContent $innerContent
     * @param string $type
     * @param \muuska\html\HtmlContent $icon
     * @param string $style
     */
    public function __construct(\muuska\html\HtmlContent $innerContent = null, $type = 'button', \muuska\html\HtmlContent $icon = null, $style = null) {
        parent::__construct($innerContent, $icon, $style);
		$this->setType($type);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\command\HtmlCommand::getOtherAttributes()
	 */
	protected function getOtherAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $attributes = parent::getOtherAttributes($globalConfig, $callerConfig);
	    if(!empty($this->type)){
	        $attributes['type'] = $this->type;
	    }
	    if(!empty($this->name)){
	        $attributes['name'] = $this->name;
	    }
	    return $attributes;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::renderStatic()
	 */
	public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return $this->drawStartTag('button', $globalConfig, $callerConfig) . $this->renderInner($globalConfig, $callerConfig).$this->drawEndTag('button', $globalConfig, $callerConfig);
	}
	
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}