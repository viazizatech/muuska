<?php
namespace muuska\html\listing\tree;

use muuska\html\listing\AbstractList;

class HtmlTree extends AbstractList{
    /**
     * @var string
     */
    protected $componentName = 'tree';
    
	/**
	 * @var \muuska\getter\Getter
	 */
	protected $subValuesGetter;
	
	/**
	 * @var int
	 */
	protected $maxDepth;
	
	/**
	 * @return boolean
	 */
	public function hasSubValuesGetter(){
	    return ($this->subValuesGetter !== null);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\listing\AbstractList::defaultCreateItem()
	 */
	public function defaultCreateItem($data, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    return $this->formatItem($this->htmls()->createTreeItem($data, $this->getItemComponentName()), $data);
	}
	
    /**
     * @return \muuska\getter\Getter
     */
    public function getSubValuesGetter()
    {
        return $this->subValuesGetter;
    }

    /**
     * @return int
     */
    public function getMaxDepth()
    {
        return $this->maxDepth;
    }

    /**
     * @param \muuska\getter\Getter $subValuesGetter
     */
    public function setSubValuesGetter(?\muuska\getter\Getter $subValuesGetter)
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
}