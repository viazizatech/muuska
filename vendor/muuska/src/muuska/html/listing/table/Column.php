<?php
namespace muuska\html\listing\table;
use muuska\html\listing\ListField;
class Column extends ListField{
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $search;
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $ascSort;
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $descSort;
    
    /**
     * @return boolean
     */
    public function hasSearch() {
        return ($this->search !== null);
    }
    
    /**
     * @return boolean
     */
    public function hasSorts() {
        return (($this->ascSort !== null) && ($this->descSort !== null));
    }
    
    /**
     * @param \muuska\html\HtmlContent $content
     */
    public function setSearch(?\muuska\html\HtmlContent $content) {
        $this->search = $content;
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @param string $prefix
     * @param string $suffix
     * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
     * @return string
     */
    public function renderSorts(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
        $result = '';
        $result = $this->renderContent($this->ascSort, $globalConfig, $callerConfig, 'ascSort', '', '', $currentCallerConfig);
        $result .= $this->renderContent($this->descSort, $globalConfig, $callerConfig, 'descSort', '', '', $currentCallerConfig);
        if(!empty($result)){
            $result = $prefix . $result . $suffix;
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
    public function renderSearch(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
        return $this->renderContent($this->search, $globalConfig, $callerConfig, 'search', $prefix, $suffix, $currentCallerConfig);
    }
    
    /**
     * @return \muuska\html\HtmlContent
     */
    public function getSearch()
    {
        return $this->search;
    }
    /**
     * @return \muuska\html\HtmlContent
     */
    public function getAscSort()
    {
        return $this->ascSort;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getDescSort()
    {
        return $this->descSort;
    }

    /**
     * @param \muuska\html\HtmlContent $ascSort
     */
    public function setAscSort(?\muuska\html\HtmlContent $ascSort)
    {
        $this->ascSort = $ascSort;
    }

    /**
     * @param \muuska\html\HtmlContent $descSort
     */
    public function setDescSort(?\muuska\html\HtmlContent $descSort)
    {
        $this->descSort = $descSort;
    }
}