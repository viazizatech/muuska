<?php
namespace muuska\html\input;

use muuska\html\HtmlElement;
use muuska\html\constants\IconPosition;

class InputIcon extends HtmlElement{
    /**
     * @var string
     */
    protected $componentName = 'input_icon';
    
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $input;
	
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $icon;
	
	/**
	 * @var int
	 */
	protected $iconPosition;
	
	/**
	 * @param \muuska\html\HtmlContent $input
	 * @param \muuska\html\HtmlContent $icon
	 * @param int $iconPosition
	 */
	public function __construct(\muuska\html\HtmlContent $input = null, \muuska\html\HtmlContent $icon = null, $iconPosition = null) {
	    $this->setInput($input);
	    $this->setIcon($icon);
	    $this->setIconPosition($iconPosition);
	}
	
	/**
	 * @return boolean
	 */
	public function isIconAtLeft() {
	    return (empty($this->iconPosition) || ($this->iconPosition == IconPosition::LEFT));
	}
	
	/**
	 * @return boolean
	 */
	public function isIconAtRight() {
	    return ($this->iconPosition == IconPosition::RIGHT);
	}
	
	/**
	 * @param string $leftClass
	 * @param string $rightClass
	 * @param boolean $addSpace
	 * @return string
	 */
	public function drawClassForIcon($leftClass, $rightClass, $addSpace = false) {
	    return $this->getStringFromCondition($this->isIconAtLeft(), $leftClass, $rightClass, $addSpace);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderInput(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContent($this->input, $globalConfig, $callerConfig, 'input', '', '', $currentCallerConfig);
	}
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderIcon(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContent($this->icon, $globalConfig, $callerConfig, 'icon', $prefix, $suffix, $currentCallerConfig);
	}
	
    /**
     * @return \muuska\html\HtmlContent
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return int
     */
    public function getIconPosition()
    {
        return $this->iconPosition;
    }

    /**
     * @param \muuska\html\HtmlContent $input
     */
    public function setInput(?\muuska\html\HtmlContent $input)
    {
        $this->input = $input;
    }

    /**
     * @param \muuska\html\HtmlContent $icon
     */
    public function setIcon(?\muuska\html\HtmlContent $icon)
    {
        $this->icon = $icon;
    }

    /**
     * @param int $iconPosition
     */
    public function setIconPosition($iconPosition)
    {
        $this->iconPosition = $iconPosition;
    }
}