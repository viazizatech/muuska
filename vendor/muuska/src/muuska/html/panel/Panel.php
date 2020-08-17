<?php
namespace muuska\html\panel;
use muuska\html\HtmlCustomElement;
class Panel extends HtmlCustomElement{
	/**
	 * @var string
	 */
    protected $componentName = 'panel';
	
	/**
	 * @var string
	 */
	protected $title;
	
	/**
	 * @var string
	 */
	protected $subTitle;
	
	/**
	 * @var \muuska\html\HtmlContent[]
	 */
	protected $mainHeaders = array();
	
	/**
	 * @var \muuska\html\HtmlContent[]
	 */
	protected $tools = array();
	
	/**
	 * @var \muuska\html\HtmlContent[]
	 */
	protected $footers = array();
	
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $icon;
	
	/**
	 * @var \muuska\html\HtmlContent
	 */
	protected $innerContent;
	
	/**
	 * @var bool
	 */
	protected $ajaxEnabled;
	
	/**
	 * @param string $title
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param \muuska\html\HtmlContent $icon
	 */
	public function __construct($title = null, \muuska\html\HtmlContent $innerContent = null, \muuska\html\HtmlContent $icon = null) {
	    $this->setTitle($title);
		$this->setIcon($icon);
		$this->setInnerContent($innerContent);
	}
	
	/**
	 * @return bool
	 */
	public function hasTools() {
	    return !empty($this->tools);
	}
	
	/**
	 * @return bool
	 */
	public function hasMainHeaders() {
	    return !empty($this->mainHeaders);
	}
	
	/**
	 * @return bool
	 */
	public function hasHeaders() {
	    return ($this->hasTitle() || $this->hasSubTitle() || $this->hasMainHeaders() || $this->hasTools());
	}
	
	/**
	 * @return bool
	 */
	public function hasFooters() {
	    return !empty($this->footers);
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasHeader($name){
	    return isset($this->mainHeaders[$name]);
	}
	
	/**
	 * @param string $name
	 * @return \muuska\html\HtmlContent
	 */
	public function getHeader($name){
	    return $this->hasHeader($name) ? $this->mainHeaders[$name] : null;
	}
	
	/**
	 * @param string $name
	 */
	public function removeHeader($name){
	    if ($this->hasHeader($name)) {
	        unset($this->mainHeaders[$name]);
	    }
	}
	
	/**
	 * @param \muuska\html\HtmlContent $content
	 */
	public function addHeader(\muuska\html\HtmlContent $content){
	    $name = $content->getName();
	    if(!empty($name)){
	        $this->mainHeaders[$name] = $content;
	    }else{
	        $this->mainHeaders[] = $content;
	    }
	}
	
	/**
	 * @param \muuska\html\HtmlContent[] $contents
	 */
	public function addHeaders($contents){
	    if (is_array($contents)) {
	        foreach ($contents as $content) {
	            $this->addHeader($content);
	        }
	    }
	}
	
	/**
	 * @param \muuska\html\HtmlContent[] $contents
	 */
	public function setHeaders($contents){
	    $this->mainHeaders = array();
	    $this->addHeaders($contents);
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasTool($name){
	    return isset($this->tools[$name]);
	}
	
	/**
	 * @param string $name
	 * @return \muuska\html\HtmlContent
	 */
	public function getTool($name){
	    return $this->hasTool($name) ? $this->tools[$name] : null;
	}
	
	/**
	 * @param string $name
	 */
	public function removeTool($name){
	    if ($this->hasTool($name)) {
	        unset($this->tools[$name]);
	    }
	}
	
	/**
	 * @param \muuska\html\HtmlContent $content
	 */
	public function addTool(\muuska\html\HtmlContent $content){
	    $name = $content->getName();
	    if(!empty($name)){
	        $this->tools[$name] = $content;
	    }else{
	        $this->tools[] = $content;
	    }
	}
	
	/**
	 * @param \muuska\html\HtmlContent[] $contents
	 */
	public function addTools($contents){
	    if (is_array($contents)) {
	        foreach ($contents as $content) {
	            $this->addTool($content);
	        }
	    }
	}
	
	/**
	 * @param \muuska\html\HtmlContent[] $contents
	 */
	public function setTools($contents){
	    $this->tools = array();
	    $this->addTools($contents);
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasFooter($name){
	    return isset($this->footers[$name]);
	}
	
	/**
	 * @param string $name
	 * @return \muuska\html\HtmlContent
	 */
	public function getFooter($name){
	    return $this->hasFooter($name) ? $this->footers[$name] : null;
	}
	
	/**
	 * @param string $name
	 */
	public function removeFooter($name){
	    if ($this->hasFooter($name)) {
	        unset($this->footers[$name]);
	    }
	}
	
	/**
	 * @param \muuska\html\HtmlContent $content
	 */
	public function addFooter(\muuska\html\HtmlContent $content){
	    $name = $content->getName();
	    if(!empty($name)){
	        $this->footers[$name] = $content;
	    }else{
	        $this->footers[] = $content;
	    }
	}
	
	/**
	 * @param \muuska\html\HtmlContent[] $contents
	 */
	public function addFooters($contents){
	    if (is_array($contents)) {
	        foreach ($contents as $content) {
	            $this->addFooter($content);
	        }
	    }
	}
	
	/**
	 * @param \muuska\html\HtmlContent[] $contents
	 */
	public function setFooters($contents){
	    $this->footers = array();
	    $this->addFooters($contents);
	}
	
	/**
	 * @return bool
	 */
	public function hasInnerContent() {
	    return ($this->innerContent !== null);
	}
	
	/**
	 * @return bool
	 */
	public function hasIcon() {
	    return ($this->icon !== null);
	}
	
	/**
	 * @return bool
	 */
	public function hasTitle() {
	    return !empty($this->title);
	}
	
	/**
	 * @return bool
	 */
	public function hasSubTitle() {
	    return !empty($this->subTitle);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $string
	 * @param string $prefix
	 * @param string $suffix
	 * @return string
	 */
	public function renderMainHeaderFromString(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $string, $prefix = '', $suffix = '') {
	    return $this->renderString($string, $globalConfig, $callerConfig, 'mainHeader', $prefix, $suffix);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $string
	 * @param string $prefix
	 * @param string $suffix
	 * @return string
	 */
	public function renderHeaderFromString(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $string, $prefix = '', $suffix = '') {
	    return $this->renderString($string, $globalConfig, $callerConfig, 'header', $prefix, $suffix);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderMainHeaders(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContentList($this->mainHeaders, $globalConfig, $callerConfig, 'mainHeaders', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderTools(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContentList($this->tools, $globalConfig, $callerConfig, 'tools', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderFooters(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContentList($this->footers, $globalConfig, $callerConfig, 'footer', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderIcon(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContent($this->icon, $globalConfig, $callerConfig, 'icon', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @return string
	 */
	public function renderTitle(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '') {
	    return $this->renderString($this->title, $globalConfig, $callerConfig, 'title', $prefix, $suffix);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @return string
	 */
	public function renderSubTitle(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '') {
	    return $this->renderString($this->subTitle, $globalConfig, $callerConfig, 'subTitle', $prefix, $suffix);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderInnerContent(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    return $this->renderContent($this->innerContent, $globalConfig, $callerConfig, 'innerContent', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlElement::getOtherClasses()
	 */
	public function getOtherClasses(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $result = parent::getOtherClasses($globalConfig, $callerConfig);
	    if($this->ajaxEnabled){
	        $result[] = 'ajax';
	    }
	    return $result;
	}
	
	/**
	 * @param string $openMode
	 */
	public function setActionDefaultOpenMode($openMode){
	    $this->addAttribute('data-action_open_mode', $openMode);
	}
	
	/**
	 * @param string $openMode
	 */
	public function setUsedOpenMode($openMode){
	    $this->addAttribute('data-used_open_mode', $openMode);
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
     * @return boolean
     */
    public function isAjaxEnabled()
    {
        return $this->ajaxEnabled;
    }

    /**
     * @param boolean $ajaxEnabled
     */
    public function setAjaxEnabled($ajaxEnabled)
    {
        $this->ajaxEnabled = $ajaxEnabled;
    }
    
    /**
     * @return \muuska\html\HtmlContent[]
     */
    public function getMainHeaders()
    {
        return $this->mainHeaders;
    }

    /**
     * @return \muuska\html\HtmlContent[]
     */
    public function getTools()
    {
        return $this->tools;
    }

    /**
     * @return \muuska\html\HtmlContent[]
     */
    public function getFooters()
    {
        return $this->footers;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return \muuska\html\HtmlContent
     */
    public function getInnerContent()
    {
        return $this->innerContent;
    }
    
    /**
     * @param \muuska\html\HtmlContent $icon
     */
    public function setIcon(?\muuska\html\HtmlContent $icon)
    {
        $this->icon = $icon;
    }

    /**
     * @param \muuska\html\HtmlContent $innerContent
     */
    public function setInnerContent(?\muuska\html\HtmlContent  $innerContent)
    {
        $this->innerContent = $innerContent;
    }
}