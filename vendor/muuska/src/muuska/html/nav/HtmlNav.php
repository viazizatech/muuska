<?php
namespace muuska\html\nav;

use muuska\html\HtmlElement;

class HtmlNav extends HtmlElement{
    /**
     * @var string
     */
    protected $componentName = 'nav';
    
    /**
     * @var HtmlNavItem[]
     */
    protected $items = array();
    
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderItems(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return parent::renderContentList($this->items, $globalConfig, $callerConfig, 'items', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param string $name
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param string $href
	 * @param \muuska\html\HtmlContent $icon
	 * @param string $title
	 * @return HtmlNavItem
	 */
	public function createItem($name, \muuska\html\HtmlContent $innerContent, $href ='#', \muuska\html\HtmlContent $icon = null, $title = ''){
	    $item = $this->htmls()->createHtmlNavItem($name, $innerContent, $href, $icon, $title);
	    $item->addAttribute('data-name', $name);
	    $this->addItem($item);
	    return $item;
	}
	
	/**
	 * @param HtmlNavItem $item
	 */
	public function addItem(HtmlNavItem $item){
	    $this->items[$item->getName()] = $item;
	}
	
	/**
	 * @param string $name
	 * @return \muuska\html\nav\HtmlNavItem
	 */
	public function getItem($name){
	    return $this->hasItem($name) ? $this->items[$name] : null;
	}
	
	/**
	 * @param string $name
	 */
	public function removeItem($name){
	    if ($this->hasItem($name)) {
	        unset($this->items[$name]);
	    }
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasItem($name){
	    return isset($this->items[$name]);
	}
	
	/**
	 * @param HtmlNavItem[] $items $items
	 */
	public function addItems($items){
	    if (is_array($items)) {
	        foreach ($items as $item) {
	            $this->addItem($item);
	        }
	    }
	}
	
    /**
     * @returnHtmlNavItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param HtmlNavItem[] $items
     */
    public function setItems($items)
    {
        $this->items = array();
        $this->addItems($items);
    }
}