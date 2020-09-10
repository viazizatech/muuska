<?php
namespace muuska\html\alert;

use muuska\html\HtmlElement;

class HtmlAlert extends HtmlElement{
    /**
     * @var string
     */
    protected $componentName = 'alert';
    
	/**
	 * @var string
	 */
	protected $type;
	
	/**
	 * @var string[]
	 */
	protected $alerts = array();
	
	/**
	 * @var string
	 */
	protected $title;
	
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $icon;
	
	/**
	 * @var bool
	 */
	protected $closeButtonEnabled;
	
	/**
	 * @var string[]
	 */
	protected $styleClasses = array();
	
	/**
	 * @var string
	 */
	protected $style;
	
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $footerContent;
	
	/**
	 * @param string $type
	 * @param string[] $alerts
	 * @param boolean $closeButtonEnabled
	 * @param string $title
	 * @param \muuska\html\HtmlContent $icon
	 */
	public function __construct($type, $alerts = array(), $closeButtonEnabled = false, $title = null, \muuska\html\HtmlContent $icon = null) {
	    $this->setType($type);
	    $this->setAlerts($alerts);
	    $this->setTitle($title);
	    $this->setCloseButtonEnabled($closeButtonEnabled);
	    if($icon !== null){
	        $this->setIcon($icon);
	    }
	}
	
	/**
	 * @param string $alert
	 */
	public function addAlert($alert) {
	    $this->alerts[] = $alert;
	}
	
	/**
	 * @param string[] $alerts
	 */
	public function addAlerts($alerts) {
	    if (is_array($alerts)) {
	        foreach ($alerts as $alert) {
	            $this->addAlert($alert);
	        }
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
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlElement::getOtherClasses()
	 */
	protected function getOtherClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
        $result = parent::getOtherClasses($globalConfig, $callerConfig);
        if($globalConfig->hasTheme()){
            $theme = $globalConfig->getTheme();
            $result = array_merge($result, $theme->getAlertClassesFromType($this->type, $this->style));
            foreach ($this->styleClasses as $class) {
                $result[] = $theme->getFinalAlertStyleClass($class);
            }
        }
        return $result;
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
	 * @return string
	 */
	public function renderTitle(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '') {
	    return $this->renderString($this->title, $globalConfig, $callerConfig, 'title', $prefix, $suffix);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderFooterContent(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    return $this->renderContent($this->footerContent, $globalConfig, $callerConfig, 'footerContent', $prefix = '', $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param string $itemPrefix
	 * @param string $itemSuffix
	 * @param string $prefix
	 * @param string $suffix
	 * @return string
	 */
	public function renderAlerts($itemPrefix = '', $itemSuffix = '', $prefix = '', $suffix = '') {
	    $result = '';
	    if(!empty($this->alerts)){
	        $result .= $prefix;
	        foreach ($this->alerts as $alert) {
	            $result .= $itemPrefix . $alert . $itemSuffix;
	        }
	        $result .= $suffix;
	    }
	    return $result;
	}
	
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string[]
     */
    public function getAlerts()
    {
        return $this->alerts;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return boolean
     */
    public function isCloseButtonEnabled()
    {
        return $this->closeButtonEnabled;
    }

    /**
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getFooterContent()
    {
        return $this->footerContent;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param string[] $alerts
     */
    public function setAlerts($alerts)
    {
        $this->alerts = array();
        $this->addAlerts($alerts);
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param \muuska\html\HtmlContent $icon
     */
    public function setIcon(?\muuska\html\HtmlContent $icon)
    {
        $this->icon = $icon;
    }

    /**
     * @param boolean $closeButtonEnabled
     */
    public function setCloseButtonEnabled($closeButtonEnabled)
    {
        $this->closeButtonEnabled = $closeButtonEnabled;
    }

    /**
     * @param string $style
     */
    public function setStyle($style)
    {
        $this->style = $style;
    }

    /**
     * @param \muuska\html\HtmlContent $footerContent
     */
    public function setFooterContent(?\muuska\html\HtmlContent $footerContent)
    {
        $this->footerContent = $footerContent;
    }
}