<?php
namespace muuska\html\listing\tree;

use muuska\html\listing\item\ListItem;
use muuska\html\listing\item\ListItemContainer;

class TreeItem extends ListItem implements ListItemContainer{
	/**
	 * @var int
	 */
	protected $depth = 1;
	
	/**
	 * @var \muuska\getter\Getter
	 */
	protected $subValuesGetter;
	
	/**
	 * @var array
	 */
	protected $subValues = array();
	
	/**
	 * @var bool
	 */
	protected $subValuesSetted;
	
	/**
	 * @var int
	 */
	protected $maxDepth;
	
	/**
	 * @var \muuska\html\listing\item\ListItemCreator
	 */
	protected $itemCreator;
	
	/**
	 * @var \muuska\renderer\HtmlContentRenderer
	 */
	protected $itemRenderer;
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $stringClasses
	 * @param array $styleAttributes
	 * @param array $attributes
	 * @param string[] $excludedAttributes
	 * @param string[] $excludedStyleAttributes
	 * @param string[] $excludedClasses
	 * @return \muuska\html\config\caller\ListItemCallerConfig
	 */
	public function createTreeItemCallerConfig(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $stringClasses = null, $styleAttributes = null, $attributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null) {
	    return $this->htmls()->createListItemCallerConfig($this->getList($globalConfig, $callerConfig), $this, $stringClasses, $styleAttributes, $attributes, $excludedAttributes, $excludedStyleAttributes, $excludedClasses);
	}

    /**
     * @param array $subValues
     */
    public function setSubValues($subValues){
        $this->subValues = $subValues;
        $this->subValuesSetted = true;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\HtmlComponent::prepare()
     */
    public function prepare(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
        parent::prepare($globalConfig, $callerConfig);
        $this->autoSetSubValues($globalConfig, $callerConfig);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\HtmlElement::getOtherClasses()
     */
    protected function getOtherClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
        $result = parent::getOtherClasses($globalConfig, $callerConfig);
        if ($this->hasSubValues($globalConfig, null, $callerConfig)) {
            $result[] = 'has_children';
        }
        return $result;
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     */
    public function autoSetSubValues(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
        if(!$this->subValuesSetted){
            $subValues = array();
            $list = $this->getList($globalConfig, $callerConfig);
            $subValueGetter = null;
            if($this->hasSubValuesGetter()){
                $subValueGetter = $this->subValuesGetter;
            }elseif(($list !== null) && ($list instanceof HtmlTree)){
                $subValueGetter = $list->getSubValuesGetter();
            }
            
            if($subValueGetter !== null){
                $subValues = $subValueGetter->get($this->data);
            }
            $this->setSubValues($subValues);
        }
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @return boolean
     */
    public function hasSubValues(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
        return (count($this->getFinalSubValues($globalConfig, $callerConfig)) > 0);
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @return array
     */
    public function getFinalSubValues(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
        $this->autoSetSubValues($globalConfig, $callerConfig);
        return $this->subValues;
    }
    
    public function drawItems(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
        $content = '';
        if($currentCallerConfig === null){
            $currentCallerConfig = $this->createTreeItemCallerConfig($globalConfig, $callerConfig);
        }
        $finalData = $this->getFinalSubValues($globalConfig, $callerConfig);
        if (is_iterable($finalData)) {
            foreach($finalData as $value){
                $item = $this->createItem($value, $globalConfig, $currentCallerConfig);
                $content .= $item->generate($globalConfig, $currentCallerConfig);
            }
        }
        return $this->drawString($content, $prefix, $suffix);
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @return \muuska\html\listing\item\ListItemCreator
     */
    public function getFinalItemCreator(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig){
        $result = $this->itemCreator;
        if($result === null){
            $list = $this->getList($globalConfig, $callerConfig);
            if($list !== null){
                $result = $list->getItemCreator();
            }
        }
        return $result;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\listing\item\ListItemContainer::createItem()
     */
    public function createItem($data, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
        $itemCreator = $this->getFinalItemCreator($globalConfig, $currentCallerConfig);
        return ($itemCreator !== null) ? $itemCreator->createItem($data, $this, $globalConfig, $currentCallerConfig) : $this->defaultCreateItem($data, $globalConfig, $currentCallerConfig);
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\listing\item\ListItemContainer::defaultCreateItem()
     */
    public function defaultCreateItem($data, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
        $list = $this->getList($globalConfig, $currentCallerConfig);
        $item = $this->htmls()->createTreeItem($data, $this->componentName);
        if($this->hasItemRenderer()){
            $item->setRenderer($this->itemRenderer);
        }elseif (($list !== null) && $list->hasItemRenderer()){
            $item->setRenderer($list->getItemRenderer());
        }
        if(($list !== null) && $list->hasIdentifierGetter()){
            $item->addAttribute('data-id', $list->getIdentifierGetter()->get($data));
        }
        return $item;
    }

    /**
     * {@inheritDoc}
     * @see \muuska\html\listing\item\ListItemContainer::hasItemCreator()
     */
    public function hasItemCreator()
    {
        return ($this->itemCreator !== null);
    }
    
    /**
     * @return boolean
     */
    public function hasItemRenderer()
    {
        return ($this->itemRenderer !== null);
    }
    
    /**
     * @return boolean
     */
    public function hasSubValuesGetter(){
        return ($this->subValuesGetter !== null);
    }
    
    /**
     * @return int
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * @return \muuska\getter\Getter
     */
    public function getSubValuesGetter()
    {
        return $this->subValuesGetter;
    }

    /**
     * @return array
     */
    public function getSubValues()
    {
        return $this->subValues;
    }

    /**
     * @return int
     */
    public function getMaxDepth()
    {
        return $this->maxDepth;
    }

    /**
     * @return \muuska\html\listing\item\ListItemCreator
     */
    public function getItemCreator()
    {
        return $this->itemCreator;
    }

    /**
     * @return \muuska\renderer\HtmlContentRenderer
     */
    public function getItemRenderer()
    {
        return $this->itemRenderer;
    }

    /**
     * @param int $depth
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;
    }

    /**
     * @param \muuska\getter\Getter $subValuesGetter
     */
    public function setSubValuesGetter($subValuesGetter)
    {
        $this->subValuesGetter = $subValuesGetter;
    }

    /**
     * @param int $maxDepth
     */
    public function setMaxDepth($maxDepth)
    {
        $this->maxDepth = $maxDepth;
    }

    /**
     * @param \muuska\html\listing\item\ListItemCreator $itemCreator
     */
    public function setItemCreator(?\muuska\html\listing\item\ListItemCreator $itemCreator)
    {
        $this->itemCreator = $itemCreator;
    }

    /**
     * @param \muuska\renderer\HtmlContentRenderer $itemRenderer
     */
    public function setItemRenderer(?\muuska\renderer\HtmlContentRenderer $itemRenderer)
    {
        $this->itemRenderer = $itemRenderer;
    }
}