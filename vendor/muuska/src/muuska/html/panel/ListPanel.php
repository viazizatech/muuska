<?php
namespace muuska\html\panel;
class ListPanel extends Panel{
    /**
     * @var string
     */
    protected $componentName = 'list_panel';
    
    /**
	 * @var \muuska\html\HtmlContent
	 */
	protected $specificSearchArea;
	
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $quickSearchArea;
	
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $sortArea;
	
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $bulkActionArea;
	
	/**
	 * @var \muuska\html\listing\ListLimiterSwitcher
	 */
	protected $limiterSwitcher;
	
	/**
	 * @var string
	 */
	protected $totalResultString;
	
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $pagination;
	
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $paginationDescription;
	
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $selectedDataIndicator;
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderPaginationDescription(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContent($this->paginationDescription, $globalConfig, $callerConfig, 'paginationDescription', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderSelectedDataIndicator(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContent($this->selectedDataIndicator, $globalConfig, $callerConfig, 'selectedDataIndicator', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderLimiterSwitcher(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContent($this->limiterSwitcher, $globalConfig, $callerConfig, 'limiterSwitcher', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderPagination(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContent($this->pagination, $globalConfig, $callerConfig, 'pagination', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderQuickSearchArea(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContent($this->quickSearchArea, $globalConfig, $callerConfig, 'quickSearchArea', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderSpecificSearchArea(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContent($this->specificSearchArea, $globalConfig, $callerConfig, 'specificSearchArea', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderBulkActionArea(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContent($this->bulkActionArea, $globalConfig, $callerConfig, 'bulkActionArea', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderSortArea(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContent($this->sortArea, $globalConfig, $callerConfig, 'sortArea', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @return string
	 */
	public function renderTotalResult(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '') {
	    return $this->renderString($this->totalResultString, $globalConfig, $callerConfig, 'totalResult', $prefix, $suffix);
	}
	
	/**
	 * @return boolean
	 */
	public function hasSpecificSearchArea() {
	    return ($this->specificSearchArea !== null);
	}
	
	/**
	 * @return boolean
	 */
	public function hasQuickSearchArea() {
	    return ($this->quickSearchArea !== null);
	}
	
	/**
	 * @return boolean
	 */
	public function hasSortArea() {
	    return ($this->sortArea !== null);
	}
	
	/**
	 * @return boolean
	 */
	public function hasBulkActionArea() {
	    return ($this->bulkActionArea !== null);
	}
	
	/**
	 * @return boolean
	 */
	public function hasLimiterSwitcher() {
	    return ($this->limiterSwitcher !== null);
	}
	
	/**
	 * @return boolean
	 */
	public function hasSelectedDataIndicator() {
	    return ($this->selectedDataIndicator !== null);
	}
	
	/**
	 * @return boolean
	 */
	public function hasPagination() {
	    return ($this->pagination !== null);
	}
	
	/**
	 * @return boolean
	 */
	public function hasPaginationDescription() {
	    return ($this->paginationDescription !== null);
	}
    /**
     * @return \muuska\html\HtmlContent
     */
    public function getSpecificSearchArea()
    {
        return $this->specificSearchArea;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getQuickSearchArea()
    {
        return $this->quickSearchArea;
    }

    /**
     * @return \muuska\html\listing\ListLimiterSwitcher
     */
    public function getLimiterSwitcher()
    {
        return $this->limiterSwitcher;
    }

    /**
     * @return string
     */
    public function getTotalResultString()
    {
        return $this->totalResultString;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getPaginationDescription()
    {
        return $this->paginationDescription;
    }

    /**
     * @param \muuska\html\HtmlContent $specificSearchArea
     */
    public function setSpecificSearchArea(?\muuska\html\HtmlContent $specificSearchArea)
    {
        $this->specificSearchArea = $specificSearchArea;
    }

    /**
     * @param \muuska\html\HtmlContent $quickSearchArea
     */
    public function setQuickSearchArea(?\muuska\html\HtmlContent $quickSearchArea)
    {
        $this->quickSearchArea = $quickSearchArea;
    }

    /**
     * @param \muuska\html\listing\ListLimiterSwitcher $limiterSwitcher
     */
    public function setLimiterSwitcher(?\muuska\html\HtmlContent $limiterSwitcher)
    {
        $this->limiterSwitcher = $limiterSwitcher;
    }

    /**
     * @param string $totalResultString
     */
    public function setTotalResultString($totalResultString)
    {
        $this->totalResultString = $totalResultString;
    }

    /**
     * @param \muuska\html\HtmlContent $pagination
     */
    public function setPagination(?\muuska\html\HtmlContent $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * @param \muuska\html\HtmlContent $paginationDescription
     */
    public function setPaginationDescription(?\muuska\html\HtmlContent $paginationDescription)
    {
        $this->paginationDescription = $paginationDescription;
    }
    /**
     * @return \muuska\html\HtmlContent
     */
    public function getSortArea()
    {
        return $this->sortArea;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getBulkActionArea()
    {
        return $this->bulkActionArea;
    }

    /**
     * @param \muuska\html\HtmlContent $sortArea
     */
    public function setSortArea(?\muuska\html\HtmlContent $sortArea)
    {
        $this->sortArea = $sortArea;
    }

    /**
     * @param \muuska\html\HtmlContent $bulkActionArea
     */
    public function setBulkActionArea(?\muuska\html\HtmlContent $bulkActionArea)
    {
        $this->bulkActionArea = $bulkActionArea;
    }
    /**
     * @return \muuska\html\HtmlContent
     */
    public function getSelectedDataIndicator()
    {
        return $this->selectedDataIndicator;
    }

    /**
     * @param \muuska\html\HtmlContent $selectedDataIndicator
     */
    public function setSelectedDataIndicator(?\muuska\html\HtmlContent $selectedDataIndicator)
    {
        $this->selectedDataIndicator = $selectedDataIndicator;
    }
}