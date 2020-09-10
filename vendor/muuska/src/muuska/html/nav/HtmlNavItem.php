<?php
namespace muuska\html\nav;

use muuska\html\command\HtmlLink;

class HtmlNavItem extends HtmlLink{
    /**
     * @var string
     */
    protected $componentName = 'nav_item';
    
    /**
     * @var bool
     */
    protected $active = false;
    
    /**
     * @var \muuska\html\HtmlContent[]
     */
    protected $subItems = array();
    
    /**
     * @var bool
     */
    protected $loaded = false;
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $navContent;
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $badge;
    
    /**
     * @param string $name
     * @param \muuska\html\HtmlContent $innerContent
     * @param string $href
     * @param \muuska\html\HtmlContent $icon
     * @param string $title
     */
    public function __construct($name, \muuska\html\HtmlContent $innerContent, $href ='#', \muuska\html\HtmlContent $icon = null, $title = '') {
        parent::__construct($innerContent, $href, $icon, $title);
        $this->setName($name);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\command\HtmlCommand::getOtherClasses()
	 */
	protected function getOtherClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $result = parent::getOtherClasses($globalConfig, $callerConfig);
	    if($this->active){
	        $result[] = 'active';
	    }
	    return $result;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\command\HtmlLink::getOtherAttributes()
	 */
	protected function getOtherAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $result = parent::getOtherAttributes($globalConfig, $callerConfig);
	    if($this->loaded){
	        $result['data-loaded'] = 1;
	    }
	    return $result;
	}
	
	
	/**
	 * @param string $trueValue
	 * @param boolean $addSpace
	 * @return string
	 */
	public function drawActive($trueValue, $addSpace = false) {
	    return $this->getStringFromCondition($this->active, $trueValue, '', $addSpace);
	}
	
	/**
	 * @return boolean
	 */
	public function hasSubItems() {
	    return !empty($this->subItems);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderSubItems(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return parent::renderContentList($this->subItems, $globalConfig, $callerConfig, 'subItems', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderBadge(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContent($this->badge, $globalConfig, $callerConfig, 'badge', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param string $name
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param string $href
	 * @param \muuska\html\HtmlContent $icon
	 * @param string $title
	 * @return HtmlNavItem
	 */
	public function createSubItem($name, \muuska\html\HtmlContent $innerContent, $href ='#', \muuska\html\HtmlContent $icon = null, $title = ''){
	    $item = $this->htmls()->createHtmlNavItem($name, $innerContent, $href, $icon, $title);
	    $item->addAttribute('data-name', $name);
	    $this->addSubItem($item);
	    return $item;
	}
	
	/**
	 * @param HtmlNavItem $item
	 */
	public function addSubItem(HtmlNavItem $item){
	    $this->subItems[$item->getName()] = $item;
	}
	
	/**
	 * @param string $name
	 * @return \muuska\html\nav\HtmlNavItem
	 */
	public function getSubItem($name){
	    return $this->hasSubItem($name) ? $this->subItems[$name] : null;
	}
	
	/**
	 * @param string $name
	 */
	public function removeSubItem($name){
	    if ($this->hasSubItem($name)) {
	        unset($this->subItems[$name]);
	    }
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasSubItem($name){
	    return isset($this->subItems[$name]);
	}
	
	/**
	 * @param HtmlNavItem[] $items $items
	 */
	public function addSubItems($items){
	    if (is_array($items)) {
	        foreach ($items as $item) {
	            $this->addSubItem($item);
	        }
	    }
	}
	
	/**
	 * @param HtmlNavItem[] $items
	 */
	public function setSubItems($items)
	{
	    $this->subItems = array();
	    $this->addSubItems($items);
	}
	
    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @return \muuska\html\HtmlContent[]
     */
    public function getSubItems()
    {
        return $this->subItems;
    }

    /**
     * @return boolean
     */
    public function isLoaded()
    {
        return $this->loaded;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getNavContent()
    {
        return $this->navContent;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getBadge()
    {
        return $this->badge;
    }

    /**
     * @param boolean $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @param boolean $loaded
     */
    public function setLoaded($loaded)
    {
        $this->loaded = $loaded;
    }

    /**
     * @param \muuska\html\HtmlContent $navContent
     */
    public function setNavContent(?\muuska\html\HtmlContent $navContent)
    {
        $this->navContent = $navContent;
    }

    /**
     * @param \muuska\html\HtmlContent $badge
     */
    public function setBadge(?\muuska\html\HtmlContent $badge)
    {
        $this->badge = $badge;
    }
}