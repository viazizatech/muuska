<?php
namespace muuska\html\dropdown;

use muuska\html\AbstractChildWrapper;

class AbstractDropdown extends AbstractChildWrapper{
    /**
     * @var bool
     */
    protected $contentVisibleOnHover = false;
    
    /**
     * @var bool
     */
    protected $defaultToggleIconEnabled = true;
    
    /**
     * @var bool
     */
    protected $childFormattingEnabled = true;
    
    /**
     * @var string
     */
    protected $activeChild;
    
    /**
     * @var string[]
     */
    protected $buttonStyleClasses = array();
    
    /**
     * @var string
     */
    protected $buttonStyle;
    
    /**
     * @var string
     */
    protected $buttonSecondStyle;
    
    /**
     * @var string[]
     */
    protected $menuClasses = array();
    
    /**
     * @param string $class
     */
    public function addMenuClass($class) {
        if (!$this->hasMenuClass($class)) {
            $this->menuClasses[] =$class;
        }
    }
    
    /**
     * @param string[] $classes
     */
    public function addMenuClasses($classes) {
        if (is_array($classes)) {
            foreach ($classes as $class) {
                $this->addMenuClass($class);
            }
        }
    }
    
    /**
     * @param string $class
     * @return boolean
     */
    public function hasMenuClass($class) {
        return in_array($class, $this->menuClasses);
    }
    
    /**
     * @param string $string
     */
    public function addMenuClassesFromString($string) {
        $classes = $this->getClassesFromString($string);
        foreach ($classes as $class) {
            $this->addMenuClass($class);
        }
    }
    
    /**
     * @param string[] $menuClasses
     */
    public function setMenuClasses($menuClasses)
    {
        $this->menuClasses = array();
        $this->addMenuClasses($menuClasses);
    }
    
    /**
     * @return string[]
     */
    public function getMenuClasses()
    {
        return $this->menuClasses;
    }
    
    /**
     * @param string $class
     */
    public function addButtonStyleClass($class) {
        if (!$this->hasButtonStyleClass($class)) {
            $this->buttonStyleClasses[] =$class;
        }
    }
    
    /**
     * @param string[] $classes
     */
    public function addButtonStyleClasses($classes) {
        if (is_array($classes)) {
            foreach ($classes as $class) {
                $this->addButtonStyleClass($class);
            }
        }
    }
    
    /**
     * @param string $class
     * @return boolean
     */
    public function hasButtonStyleClass($class) {
        return in_array($class, $this->buttonStyleClasses);
    }
    
    /**
     * @param string $string
     */
    public function addButtonStyleClassesFromString($string) {
        $classes = $this->getClassesFromString($string);
        foreach ($classes as $class) {
            $this->addButtonStyleClass($class);
        }
    }
    
    /**
     * @param string[] $styleClasses
     */
    public function setButtonStyleClasses($styleClasses)
    {
        $this->buttonStyleClasses = array();
        $this->addButtonStyleClasses($styleClasses);
    }
    
    /**
     * @return string[]
     */
    public function getButtonStyleClasses()
    {
        return $this->buttonStyleClasses;
    }
    
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $class
	 * @param string $prefix
	 * @param string $suffix
	 * @return string
	 */
	public function generateChildrenWithClass(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $class, $prefix = '', $suffix = '') {
	    $result = '';
	    foreach ($this->children as $key => $child) {
	        if($this->childFormattingEnabled){
	            $currentCallerConfig = $this->createCallerConfig($class);
	            if($key === $this->activeChild){
	                $currentCallerConfig->addClass('active');
	            }
	        }
	        $result .= $child->generate($globalConfig, $currentCallerConfig);
	    }
	    return $this->drawString($result, $prefix, $suffix);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param string $stringClasses
	 * @param string[] $excludedClasses
	 * @param boolean $addSpace
	 * @param boolean $addClassAttribute
	 * @return string
	 */
	public function drawButtonClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, $stringClasses = null, $excludedClasses = null, $addSpace = true, $addClassAttribute = true){
	    $classes = $globalConfig->hasTheme() ? $globalConfig->getTheme()->getCommandClasses($this->buttonStyle, $this->buttonSecondStyle) : array();
	    if(!empty($this->buttonStyleClasses)){
	        $theme = $globalConfig->getTheme();
	        if($theme !== null){
	            foreach ($this->buttonStyleClasses as $class) {
	                $classes[] = $theme->getFinalCommandStyleClass($class);
	            }
	        }else{
	            $classes = array_merge($classes, $this->buttonStyleClasses);
	        }
	    }
	    
	    return $this->drawClassesFromList($classes, null, $addSpace, $addClassAttribute, $stringClasses, $excludedClasses);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param string $stringClasses
	 * @param string[] $excludedClasses
	 * @param boolean $addSpace
	 * @param boolean $addClassAttribute
	 * @return string
	 */
	public function drawMenuClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, $stringClasses = null, $excludedClasses = null, $addSpace = true, $addClassAttribute = true){
	    return $this->drawClassesFromList($this->menuClasses, null, $addSpace, $addClassAttribute, $stringClasses, $excludedClasses);
	}
	
    /**
     * @return boolean
     */
    public function isContentVisibleOnHover()
    {
        return $this->contentVisibleOnHover;
    }

    /**
     * @return boolean
     */
    public function isDefaultToggleIconEnabled()
    {
        return $this->defaultToggleIconEnabled;
    }

    /**
     * @return boolean
     */
    public function isChildFormattingEnabled()
    {
        return $this->childFormattingEnabled;
    }

    /**
     * @param boolean $contentVisibleOnHover
     */
    public function setContentVisibleOnHover($contentVisibleOnHover)
    {
        $this->contentVisibleOnHover = $contentVisibleOnHover;
    }

    /**
     * @param boolean $defaultToggleIconEnabled
     */
    public function setDefaultToggleIconEnabled($defaultToggleIconEnabled)
    {
        $this->defaultToggleIconEnabled = $defaultToggleIconEnabled;
    }

    /**
     * @param boolean $childFormattingEnabled
     */
    public function setChildFormattingEnabled($childFormattingEnabled)
    {
        $this->childFormattingEnabled = $childFormattingEnabled;
    }
    
    /**
     * @return string
     */
    public function getButtonStyle()
    {
        return $this->buttonStyle;
    }
    
    /**
     * @return string
     */
    public function getButtonSecondStyle()
    {
        return $this->buttonSecondStyle;
    }
    
    /**
     * @param string $style
     */
    public function setButtonStyle($buttonStyle)
    {
        $this->buttonStyle = $buttonStyle;
    }
    
    /**
     * @param string $buttonSecondStyle
     */
    public function setButtonSecondStyle($buttonSecondStyle)
    {
        $this->buttonSecondStyle = $buttonSecondStyle;
    }
    
    /**
     * @return string
     */
    public function getActiveChild()
    {
        return $this->activeChild;
    }

    /**
     * @param string $activeChild
     */
    public function setActiveChild($activeChild)
    {
        $this->activeChild = $activeChild;
    }
}