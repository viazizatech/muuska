<?php
namespace muuska\html\listing\item;
use muuska\html\command\HtmlLink;
class ItemAction extends HtmlLink implements ItemActionCreator{
	/**
	 * @var \muuska\url\objects\ObjectUrl
	 */
	protected $urlCreator;
	
	/**
	 * @param string $name
	 * @param \muuska\url\objects\ObjectUrl $urlCreator
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param \muuska\html\HtmlContent $icon
	 * @param string $title
	 * @param boolean $buttonStyleEnabled
	 * @param string $style
	 */
	public function __construct($name, \muuska\url\objects\ObjectUrl $urlCreator = null, \muuska\html\HtmlContent $innerContent = null, \muuska\html\HtmlContent $icon = null, $title = '', $buttonStyleEnabled = false, $style = null) {
	    parent::__construct($innerContent, null, $icon, $title, $buttonStyleEnabled, $style);
	    $this->setName($name);
	    $this->setUrlCreator($urlCreator);
	}
	
	/**
	 * @return boolean
	 */
	public function hasUrlCreator() {
	    return ($this->urlCreator !== null);
	}
	
	/**
	 * @param mixed $data
	 * @param array $params
	 * @param string $anchor
	 * @param int $mode
	 * @return string
	 */
	public function createUrl($data, $params = array(), $anchor = '', $mode = null) {
	    $url = '#';
	    if($this->hasUrlCreator()){
	        $url = $this->urlCreator->createUrl($data, $params, $anchor, $mode);
	    }
	    return $url;
	}

    /**
     * @return \muuska\url\objects\ObjectUrl
     */
    public function getUrlCreator()
    {
        return $this->urlCreator;
    }

    /**
     * @param \muuska\url\objects\ObjectUrl $urlCreator
     */
    public function setUrlCreator(?\muuska\url\objects\ObjectUrl $urlCreator)
    {
        $this->urlCreator = $urlCreator;
    }
    
    /**
     * {@inheritDoc}
     * @see \muuska\html\listing\item\ItemActionCreator::createAction()
     */
    public function createAction($itemData, $item, $urlParams = array(), $anchor = '', $mode = null)
    {
        $link = clone $this;
        $link->setHref($this->createUrl($itemData, $urlParams, $anchor, $mode));
        return $link;
    }
}