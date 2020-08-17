<?php
namespace muuska\html\listing\table;
use muuska\html\listing\AbstractList;
class Table extends AbstractList{
    /**
     * @var string
     */
    protected $componentName = 'table';
    
    /**
     * @var bool
     */
    protected $searchResetEnabled;
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $searchAction;
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $searchResetAction;
    
    /**
     * @var bool
     */
    protected $headerDisabled;
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\listing\AbstractList::createField()
     */
    public function createField($name, \muuska\renderer\value\ValueRenderer $valueRenderer = null, $label = null) {
        $field = $this->htmls()->createColumn($name, $valueRenderer, $label);
	    $this->addField($field);
	    return $field;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\listing\AbstractList::needActionsBlock()
	 */
	public function needActionsBlock() {
	    return (parent::needActionsBlock() || $this->hasSearchFields());
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderSearchActions(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    $result = '';
	    if($this->needActionsBlock()){
	        $result =  $this->renderContent($this->searchAction, $globalConfig, $callerConfig, 'searchAction', '', '', $currentCallerConfig);
	        if($this->isSearchResetEnabled()){
	            $result .= $this->renderContent($this->searchResetAction, $globalConfig, $callerConfig, 'searchResetAction', '', '', $currentCallerConfig);
	        }
	        $result = $prefix . $result . $suffix;
	    }
	    return $result;
	}
	
	/**
	 * @return boolean
	 */
	public function hasSearchFields() {
	    $result = false;
	    foreach ($this->fields as $field){
	        if($field->hasSearch()){
	            $result = true;
	            break;
	        }
	    }
	    return $result;
	}
	
    /**
     * @return boolean
     */
    public function isSearchResetEnabled()
    {
        return $this->searchResetEnabled;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getSearchAction()
    {
        return $this->searchAction;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getSearchResetAction()
    {
        return $this->searchResetAction;
    }

    /**
     * @param boolean $searchResetEnabled
     */
    public function setSearchResetEnabled($searchResetEnabled)
    {
        $this->searchResetEnabled = $searchResetEnabled;
    }

    /**
     * @param \muuska\html\HtmlContent $searchAction
     */
    public function setSearchAction(?\muuska\html\HtmlContent $searchAction)
    {
        $this->searchAction = $searchAction;
    }

    /**
     * @param \muuska\html\HtmlContent $searchResetAction
     */
    public function setSearchResetAction(?\muuska\html\HtmlContent $searchResetAction)
    {
        $this->searchResetAction = $searchResetAction;
    }
    
    /**
     * @return boolean
     */
    public function isHeaderDisabled()
    {
        return $this->headerDisabled;
    }

    /**
     * @param boolean $headerDisabled
     */
    public function setHeaderDisabled($headerDisabled)
    {
        $this->headerDisabled = $headerDisabled;
    }
}