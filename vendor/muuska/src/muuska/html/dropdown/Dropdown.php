<?php
namespace muuska\html\dropdown;

use muuska\html\constants\IconPosition;

class Dropdown extends AbstractDropdown{
    /**
     * @var string
     */
    protected $componentName = 'default_dropdown';
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $icon;
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $innerContent;
    
    /**
     * @var int
     */
    protected $iconPosition;
    
    /**
     * @param \muuska\html\HtmlContent $innerContent
     * @param \muuska\html\HtmlContent $icon
     */
    public function __construct(\muuska\html\HtmlContent $innerContent = null, \muuska\html\HtmlContent $icon = null) {
        $this->setInnerContent($innerContent);
        $this->setIcon($icon);
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
	    return $this->renderContent($this->icon, $globalConfig, $callerConfig, 'icon', $prefix = '', $suffix, $currentCallerConfig);
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
     * @return \muuska\html\HtmlContent
     */
    public function getIcon()
    {
        return $this->icon;
    }
    

    /**
     * @param \muuska\html\HtmlContent $icon
     */
    public function setIcon(?\muuska\html\HtmlContent $icon)
    {
        $this->icon = $icon;
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
    public function setInnerContent(?\muuska\html\HtmlContent $innerContent)
    {
        $this->innerContent = $innerContent;
    }
    
    /**
     * @param int $iconPosition
     */
    public function setIconPosition($iconPosition)
    {
        $this->iconPosition = $iconPosition;
    }
    
    /**
     * @return int
     */
    public function getIconPosition()
    {
        return $this->iconPosition;
    }
}