<?php
namespace muuska\html;

class HtmlImage extends HtmlElement{
    /**
	 * @var string
	 */
	protected $src;
	
	/**
	 * @var string
	 */
	protected $alt;
	
	/**
	 * @var string
	 */
	protected $title;
	
	/**
	 * @param string $src
	 * @param string $alt
	 * @param string $title
	 */
	public function __construct($src, $alt = '', $title = '') {
	    $this->componentName = 'image';
	    $this->setSrc($src);
		$this->setAlt($alt);
		$this->setTitle($title);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlElement::getOtherAttributes()
	 */
	protected function getOtherAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $attributes = parent::getOtherAttributes($globalConfig, $callerConfig);
	    $src = $this->getFinalSrc($globalConfig, $callerConfig);
	    if(!empty($src)){
	        $attributes['src'] = $src;
	    }
	    if(!empty($this->alt)){
	        $attributes['alt'] = $this->alt;
	    }
	    if(!empty($this->title)){
	        $attributes['title'] = $this->title;
	    }
	    return $attributes;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::renderStatic()
	 */
	public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return '<img' . $this->drawAllAttributes($globalConfig, $callerConfig) . ' />';
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return string
	 */
	public function getFinalSrc(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return $this->src;
	}
	
    /**
     * @return string
     */
    public function getSrc()
    {
        return $this->src;
    }

    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $src
     */
    public function setSrc($src)
    {
        $this->src = $src;
    }

    /**
     * @param string $alt
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}