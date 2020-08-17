<?php
namespace muuska\html\listing\pagination;
use muuska\html\HtmlElement;
class Pagination extends HtmlElement{
    /**
     * @var string
     */
    protected $componentName = 'pagination';
    
	/**
	 * @var int
	 */
	protected $totalResult = 0;
	
	/**
	 * @var int
	 */
	protected $itemsPerPage = 20;
	
	/**
	 * @var int
	 */
	protected $currentPage = 1;
	
	/**
	 * @var int
	 */
	protected $maxPageDisplayed = 5;
	
	/**
	 * @var \muuska\url\pagination\PaginationUrl
	 */
	protected $urlCreator;
	
	/**
	 * @var int
	 */
	protected $totalPage;
	
	/**
	 * @var string[]
	 */
	protected $linkClasses = array();
	
	/**
	 * @param int $totalResult
	 * @param int $itemsPerPage
	 * @param int $currentPage
	 * @param int $maxPageDisplayed
	 * @param \muuska\url\pagination\PaginationUrl $urlCreator
	 */
	public function __construct($totalResult, $itemsPerPage = 20, $currentPage = 1, $maxPageDisplayed = 5, \muuska\url\pagination\PaginationUrl $urlCreator = null) {
		$this->setTotalResult($totalResult);
		$this->setItemsPerPage($itemsPerPage);
		$this->setCurrentPage($currentPage);
		$this->setMaxPageDisplayed($maxPageDisplayed);
		$this->setUrlCreator($urlCreator);
	}
	
	/**
	 * @return boolean
	 */
	public function isFirstEnabled() {
		return $this->currentPage != 1;
	}
	
	/**
	 * @return boolean
	 */
	public function isLastEnabled() {
		return $this->currentPage != $this->totalPage;
	}
	
	/**
	 * @return boolean
	 */
	public function isPrevEnabled() {
		return $this->currentPage != $this->getPrevPage();
	}
	
	/**
	 * @return boolean
	 */
	public function isNextEnabled() {
		return $this->currentPage != $this->getNextPage();
	}
	
	/**
	 * @return int
	 */
	public function getNextPage() {
		return (($this->currentPage + 1) > $this->totalPage) ? $this->currentPage : $this->currentPage + 1;
	}
	
	/**
	 * @return int
	 */
	public function getPrevPage() {
		return (($this->currentPage-1) == 0) ? $this->currentPage : $this->currentPage - 1;
	}
	
	/**
	 * @return string
	 */
	public function getFirstUrl() {
	    return $this->getPageUrl(1, $this->isFirstEnabled());
	}
	
	/**
	 * @return string
	 */
	public function getLastUrl() {
	    return $this->getPageUrl($this->totalPage, $this->isLastEnabled());
	}
	
	/**
	 * @return string
	 */
	public function getPrevUrl() {
	    return $this->getPageUrl($this->getPrevPage(), $this->isPrevEnabled());
	}
	
	/**
	 * @return string
	 */
	public function getNextUrl() {
	    return $this->getPageUrl($this->getNextPage(), $this->isNextEnabled());
	}
	
	/**
	 * @param string $stringClasses
	 * @param boolean $addSpace
	 * @param boolean $addClassAttribute
	 * @param string[] $classesToExclude
	 * @return string
	 */
	public function drawLinkClasses($stringClasses = null, $addSpace = true, $addClassAttribute = false, $excludedClasses = null) {
	    return $this->drawClassesFromList($this->linkClasses, null, $addSpace, $addClassAttribute, $stringClasses, $excludedClasses);
	}
	
	/**
	 * @param int $page
	 * @param boolean $enabled
	 * @return string
	 */
	public function getPageUrl($page, $enabled = true) {
	    return ($this->hasUrlCreator() && $enabled) ? $this->urlCreator->createPageUrl($page) : 'javascript:;';
	}
	
	/**
	 * @return bool
	 */
	public function hasUrlCreator() {
	    return ($this->urlCreator !== null);
	}
	
	/**
	 * @return int
	 */
	public function getStartPage() {
	    $start = $this->currentPage;
	    $maxPageDisplayed = $this->maxPageDisplayed - 1;
	    if($maxPageDisplayed > 0){
	        $middle = (int)($maxPageDisplayed / 2);
	        $divRemain = $maxPageDisplayed % 2;
	        $expectedMax = $this->currentPage + $middle + $divRemain;
	        if($expectedMax > $this->totalPage){
	            $expectedMax = $this->totalPage;
	        }
	        $expectedStart = $expectedMax - $maxPageDisplayed;
	        $start = ($expectedStart < 1) ? 1 : $expectedStart;
	    }
	    return $start;
	}
	
	/**
	 * @return int
	 */
	public function getEndPage() {
		$start = $this->getStartPage();
		$end = $start -1 + $this->maxPageDisplayed;
		return (($this->totalPage - $end)<0) ? $this->totalPage : $end;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::prepare()
	 */
	public function prepare(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    parent::prepare($globalConfig, $callerConfig);
	    if(!empty($this->itemsPerPage)){
			$this->totalPage = ceil($this->totalResult / $this->itemsPerPage);
		}
	}
	
	/**
	 * @param int $page
	 * @param string $class
	 * @param boolean $addSpace
	 * @param boolean $addClassAttribute
	 * @return string
	 */
	public function drawActive($page, $class= 'active', $addSpace = true, $addClassAttribute = false) {
	    return ($this->currentPage == $page) ? $this->drawClassFromString($class, $addSpace, $addClassAttribute) : '';
	}
	
    /**
     * @return int
     */
    public function getTotalResult()
    {
        return $this->totalResult;
    }

    /**
     * @return int
     */
    public function getItemsPerPage()
    {
        return $this->itemsPerPage;
    }

    /**
     * @return int
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @return int
     */
    public function getMaxPageDisplayed()
    {
        return $this->maxPageDisplayed;
    }

    /**
     * @return \muuska\url\pagination\PaginationUrl
     */
    public function getUrlCreator()
    {
        return $this->urlCreator;
    }

    /**
     * @return int
     */
    public function getTotalPage()
    {
        return $this->totalPage;
    }

    /**
     * @return string[]
     */
    public function getLinkClasses()
    {
        return $this->linkClasses;
    }

    /**
     * @param int $totalResult
     */
    public function setTotalResult($totalResult)
    {
        $this->totalResult = $totalResult;
    }

    /**
     * @param int $itemsPerPage
     */
    public function setItemsPerPage($itemsPerPage)
    {
        $this->itemsPerPage = $itemsPerPage;
    }

    /**
     * @param int $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @param int $maxPageDisplayed
     */
    public function setMaxPageDisplayed($maxPageDisplayed)
    {
        $this->maxPageDisplayed = $maxPageDisplayed;
    }

    /**
     * @param \muuska\url\pagination\PaginationUrl $urlCreator
     */
    public function setUrlCreator(?\muuska\url\pagination\PaginationUrl $urlCreator)
    {
        $this->urlCreator = $urlCreator;
    }

    /**
     * @param int $totalPage
     */
    public function setTotalPage($totalPage)
    {
        $this->totalPage = $totalPage;
    }

    /**
     * @param string[] $linkClasses
     */
    public function setLinkClasses($linkClasses)
    {
        $this->linkClasses = $linkClasses;
    }
}