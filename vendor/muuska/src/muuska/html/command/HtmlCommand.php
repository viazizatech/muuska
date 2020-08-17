<?php
namespace muuska\html\command;

use muuska\html\HtmlElement;
use muuska\html\constants\IconPosition;

abstract class HtmlCommand extends HtmlElement{
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $innerContent;
    
	/**
	 * @var string
	 */
	protected $confirmText;
	/**
	 * @var bool
	 */
	protected $confirm;
	/**
	 * @var bool
	 */
	protected $autoConfirm;
	
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $icon;
	
	/**
	 * @var string[]
	 */
	protected $styleClasses = array();
	
	/**
	 * @var string
	 */
	protected $action;
	
	/**
	 * @var int
	 */
	protected $iconPosition;
	
	/**
	 * @var string
	 */
	protected $style;
	
	/**
	 * @var string
	 */
	protected $secondStyle;
	
	/**
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param \muuska\html\HtmlContent $icon
	 * @param string $style
	 */
	public function __construct(\muuska\html\HtmlContent $innerContent = null, \muuska\html\HtmlContent $icon = null, $style = null) {
		$this->setInnerContent($innerContent);
		$this->setStyle($style);
		if($icon !== null){
		    $this->setIcon($icon);
		}
	}
	
	/**
	 * @param string $class
	 */
	public function addStyleClass($class) {
	    if (!$this->hasStyleClass($class)) {
	        $this->styleClasses[] =$class;
	    }
	}
	
	/**
	 * @param string[] $classes
	 */
	public function addStyleClasses($classes) {
	    if (is_array($classes)) {
	        foreach ($classes as $class) {
	            $this->addStyleClass($class);
	        }
	    }
	}
	
	/**
	 * @param string $class
	 * @return boolean
	 */
	public function hasStyleClass($class) {
	    return in_array($class, $this->styleClasses);
	}
	
	/**
	 * @param string $string
	 */
	public function addStyleClassesFromString($string) {
	    $classes = $this->getClassesFromString($string);
	    foreach ($classes as $class) {
	        $this->addStyleClass($class);
	    }
	}
	
	/**
	 * @param string[] $styleClasses
	 */
	public function setStyleClasses($styleClasses)
	{
	    $this->styleClasses = array();
	    $this->addStyleClasses($styleClasses);
	}
	
	/**
	 * @return string[]
	 */
	public function getStyleClasses()
	{
	    return $this->styleClasses;
	}
	
	/**
	 * @return boolean
	 */
	public function hasIcon() {
	    return ($this->icon !== null);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::prepare()
	 */
	public function prepare(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
		if($this->confirm){
			$this->addAttribute('confirm_text', $this->confirmText);
			$this->addClass('confirm_command');
			if($this->autoConfirm){
				$this->addClass('auto_confirm');
			}
		}
		parent::prepare($globalConfig, $callerConfig);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlElement::getOtherAttributes()
	 */
	protected function getOtherAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $attributes = parent::getOtherAttributes($globalConfig, $callerConfig);
	    if(!empty($this->action)){
	        $attributes['data-action'] = $this->action;
	    }
	    return $attributes;
	}
	
	public function disableAjax() {
	    $this->addClass('no_ajax');
	}
	
	/**
	 * @param string $openMode
	 */
	public function setOpenMode($openMode) {
	    $this->addAttribute('data-open_mode', $openMode);
	}
	
	/**
	 * @return boolean
	 */
	public function hasInnerContent() {
	    return ($this->innerContent !== null);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderIcon(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    return $this->renderContent($this->icon, $globalConfig, $callerConfig, 'icon', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderInnerContent(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    return $this->renderContent($this->innerContent, $globalConfig, $callerConfig, 'innerContent', $prefix, $suffix);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return string
	 */
	public function renderInner(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig){
	    $result = ((empty($this->iconPosition) || ($this->iconPosition == IconPosition::LEFT)) ? $this->renderIcon($globalConfig, $callerConfig, '', ' ') : '').$this->renderInnerContent($globalConfig, $callerConfig);
	    if($this->iconPosition == IconPosition::RIGHT){
	        $result .= $this->renderIcon($globalConfig, $callerConfig, $this->getStringFromCondition(!empty($result), ' '));
	    }
	    return $result;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlElement::getOtherClasses()
	 */
	protected function getOtherClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
        $result = parent::getOtherClasses($globalConfig, $callerConfig);
        $result = array_merge($result, $this->getCommandClasses($globalConfig, $callerConfig));
        
        $result = array_merge($result, $this->getFormattedStyleClasses($globalConfig, $callerConfig));
        return $result;
    }
	
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @return string[]
     */
    public function getFormattedStyleClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    $result = array();
	    if(!empty($this->styleClasses)){
	        $theme = $globalConfig->getTheme();
	        if($theme !== null){
	            foreach ($this->styleClasses as $class) {
	                $result[] = $theme->getFinalCommandStyleClass($class);
	            }
	        }else{
	            $result = $this->styleClasses;
	        }
	        
	    }
	    return $result;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return string[]
	 */
	public function getCommandClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return $globalConfig->hasTheme() ? $globalConfig->getTheme()->getCommandClasses($this->style, $this->secondStyle) : array();
	}

    /**
     * @return string
     */
    public function getConfirmText()
    {
        return $this->confirmText;
    }

    /**
     * @return boolean
     */
    public function isConfirm()
    {
        return $this->confirm;
    }

    /**
     * @return boolean
     */
    public function isAutoConfirm()
    {
        return $this->autoConfirm;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getIcon()
    {
        return $this->icon;
    }


    /**
     * @param string $confirmText
     */
    public function setConfirmText($confirmText)
    {
        $this->confirmText = $confirmText;
    }

    /**
     * @param boolean $confirm
     */
    public function setConfirm($confirm)
    {
        $this->confirm = $confirm;
    }

    /**
     * @param boolean $autoConfirm
     */
    public function setAutoConfirm($autoConfirm)
    {
        $this->autoConfirm = $autoConfirm;
    }

    /**
     * @param \muuska\html\HtmlContent $icon
     */
    public function setIcon(?\muuska\html\HtmlContent $icon)
    {
        $this->icon = $icon;
    }

    /**
     * @return int
     */
    public function getIconPosition()
    {
        return $this->iconPosition;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getInnerContent()
    {
        return $this->innerContent;
    }

    /**
     * @param int $iconPosition
     */
    public function setIconPosition($iconPosition)
    {
        $this->iconPosition = $iconPosition;
    }

    /**
     * @param \muuska\html\HtmlContent $innerContent
     */
    public function setInnerContent(?\muuska\html\HtmlContent $innerContent)
    {
        $this->innerContent = $innerContent;
    }
    
    /**
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @return string
     */
    public function getSecondStyle()
    {
        return $this->secondStyle;
    }

    /**
     * @param string $style
     */
    public function setStyle($style)
    {
        $this->style = $style;
    }

    /**
     * @param string $secondStyle
     */
    public function setSecondStyle($secondStyle)
    {
        $this->secondStyle = $secondStyle;
    }
}