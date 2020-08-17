<?php
namespace muuska\html\nav;

use muuska\html\HtmlElement;

class FullNavigation extends HtmlElement{
    /**
     * @var string
     */
    protected $componentName = 'full_navigation';
    
    /**
     * @var \muuska\html\nav\HtmlNav
     */
    protected $nav;
    
    /**
     * @var bool
     */
    protected $ajaxEnabled = true;
    
    /**
     * @param \muuska\html\nav\HtmlNav $nav
     */
    public function __construct(\muuska\html\nav\HtmlNav $nav = null) {
        $this->setNav($nav);
	}
	
	/**
	 * @return boolean
	 */
	public function hasNav(){
	    return ($this->nav !== null);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderNav(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return parent::renderContent($this->nav, $globalConfig, $callerConfig, 'nav', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderNavContents(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    $result = '';
	    if($this->nav !== null){
	        $navItems = $this->nav->getItems();
	        foreach ($navItems as $item) {
	            $result .= '<div class="nav_content" data-name="'.$item->getName().'"'.$this->getStringFromCondition(!$item->isActive(), 'style="display:none;"', '', true).'>'.$this->renderContent($item->getNavContent(), $globalConfig, $currentCallerConfig, '').'</div>';
	        }
	    }
	    if(!empty($result)){
	        $result = $prefix . $result . $suffix;
	    }
	    return $result;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlElement::getOtherClasses()
	 */
	public function getOtherClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $result = parent::getOtherClasses($globalConfig, $callerConfig);
	    if($this->ajaxEnabled){
	        $result[] = 'ajax';
	    }
	    return $result;
	}
	
    /**
     * @return \muuska\html\nav\HtmlNav
     */
    public function getNav()
    {
        return $this->nav;
    }

    /**
     * @param \muuska\html\nav\HtmlNav $nav
     */
    public function setNav(?\muuska\html\nav\HtmlNav $nav)
    {
        $this->nav = $nav;
    }
}