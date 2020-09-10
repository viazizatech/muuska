<?php
namespace muuska\html\form;

use muuska\html\constants\IconPosition;

class QuickSearchForm extends Form{
    /**
     * @var string
     */
    protected $componentName = 'quick_search_form';
    
    /**
     * @var bool
     */
    protected $iconEnabled;
    
    /**
     * @var int
     */
    protected $iconPosition;
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $customIcon;
    
    /**
     * @param string $action
     * @param \muuska\html\HtmlContent $input
     * @param boolean $iconEnabled
     * @param int $iconPosition
     */
    public function __construct($action = '',  \muuska\html\HtmlContent $input = null, $iconEnabled = true, $iconPosition = null) {
        parent::__construct($action);
        $this->setIconEnabled($iconEnabled);
        $this->setIconPosition($iconPosition);
        if($input !== null){
            $this->addChild($input);
        }
    }
    
    /**
     * @return boolean
     */
    public function hasCustomIcon() {
        return ($this->customIcon !== null);
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
     * @param string $defaultIconHtml
     * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
     * @return string
     */
    public function renderIcon(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $defaultIconHtml, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
        return ($this->hasCustomIcon() ? $this->renderContent($this->customIcon, $globalConfig, $callerConfig, 'icon', '', '', $currentCallerConfig) : $defaultIconHtml);
    }
    
    /**
     * @return boolean
     */
    public function isIconEnabled()
    {
        return $this->iconEnabled;
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
    public function getCustomIcon()
    {
        return $this->customIcon;
    }

    /**
     * @param boolean $iconEnabled
     */
    public function setIconEnabled($iconEnabled)
    {
        $this->iconEnabled = $iconEnabled;
    }

    /**
     * @param int $iconPosition
     */
    public function setIconPosition($iconPosition)
    {
        $this->iconPosition = $iconPosition;
    }

    /**
     * @param \muuska\html\HtmlContent $customIcon
     */
    public function setCustomIcon(?\muuska\html\HtmlContent $customIcon)
    {
        $this->customIcon = $customIcon;
    }
}