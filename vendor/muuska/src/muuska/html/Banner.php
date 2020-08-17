<?php
namespace muuska\html;
class Banner extends HtmlElement{
    /**
     * @var string
     */
    protected $componentName = 'banner';
    
    /**
     * @var string
     */
    protected $title;
    
    /**
     * @var string
     */
    protected $subTitle;
    
    /**
     * @var string
     */
    protected $backgroundImageUrl;
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $mainImage;
    
    /**
     * @var \muuska\html\HtmlContent
     */
    protected $mainLink;
    
    /**
     * @param \muuska\html\HtmlImage $mainImage
     * @param string $title
     * @param string $subTitle
     * @param \muuska\html\HtmlContent $mainLink
     * @param string $backgroundImageUrl
     */
    public function __construct(\muuska\html\HtmlImage $mainImage, $title = null, $subTitle = null, \muuska\html\HtmlContent $mainLink = null, $backgroundImageUrl = null) {
		$this->setMainImage($mainImage);
		$this->setTitle($title);
		$this->setSubTitle($subTitle);
		$this->setMainLink($mainLink);
		$this->setBackgroundImageUrl($backgroundImageUrl);
	}
	
	public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    $result = '';
	    
	    return $result;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlElement::getOtherStyleAttributes()
	 */
	protected function getOtherStyleAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $result = parent::getOtherStyleAttributes($globalConfig, $callerConfig);
	    if($this->hasBackgroundImageUrl()){
	        $result['background-image'] = 'url(\''.$this->backgroundImageUrl.'\')';
	    }
	    return $result;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlElement::getOtherClasses()
	 */
	protected function getOtherClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $result = parent::getOtherClasses($globalConfig, $callerConfig);
	    if(!$this->hasMainImage()){
	        $result[] = 'no_main_image';
	    }
	    return $result;
	}
	
	/**
	 * @return bool
	 */
	public function hasMainImage(){
	    return ($this->mainImage !== null);
	}
	
	/**
	 * @return bool
	 */
	public function hasMainLink(){
	    return ($this->mainLink !== null);
	}
	
	/**
	 * @return bool
	 */
	public function hasTitle(){
	    return !empty($this->title);
	}
	
	/**
	 * @return bool
	 */
	public function hasSubTitle(){
	    return !empty($this->subTitle);
	}
	
	/**
	 * @return bool
	 */
	public function hasBackgroundImageUrl(){
	    return !empty($this->backgroundImageUrl);
	}
	
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getSubTitle()
    {
        return $this->subTitle;
    }

    /**
     * @return string
     */
    public function getBackgroundImageUrl()
    {
        return $this->backgroundImageUrl;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getMainLink()
    {
        return $this->mainLink;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string $subTitle
     */
    public function setSubTitle($subTitle)
    {
        $this->subTitle = $subTitle;
    }

    /**
     * @param string $backgroundImageUrl
     */
    public function setBackgroundImageUrl($backgroundImageUrl)
    {
        $this->backgroundImageUrl = $backgroundImageUrl;
    }

    /**
     * @param \muuska\html\HtmlContent $mainImage
     */
    public function setMainImage(?\muuska\html\HtmlContent $mainImage)
    {
        $this->mainImage = $mainImage;
    }

    /**
     * @param \muuska\html\HtmlContent $mainLink
     */
    public function setMainLink(?\muuska\html\HtmlContent $mainLink)
    {
        $this->mainLink = $mainLink;
    }
}