<?php
namespace muuska\html;

use muuska\asset\constants\LocationInPage;
use muuska\util\App;
use muuska\constants\Names;

class HtmlPage extends HtmlCustomElement{
    /**
     * @var string
     */
    protected $componentName = 'page';
    
    /**
     * @var string
     */
    protected $title;
    
    /**
     * @var string
     */
    protected $langIso;
    
    /**
     * @var string
     */
    protected $bodyId;
    
    /**
     * @var array
     */
    protected $bodyClasses = array();
    
    /**
     * @var array
     */
    protected $bodyAttributes = array();
    
    /**
     * @var bool
     */
    protected $bodyVisible = true;
    
    /**
     * @var array
     */
    protected $bodyStyleAttributes = array();
    
    /**
     * @param string $title
     * @param \muuska\html\areacreator\AreaCreator $areaCreator
     * @param \muuska\renderer\HtmlContentRenderer $renderer
     */
    public function __construct($title, \muuska\html\areacreator\AreaCreator $areaCreator = null, \muuska\renderer\HtmlContentRenderer $renderer = null){
        parent::__construct($areaCreator, $renderer);
        $this->setTitle($title);
    }
	
	/**
	 * @param string $string
	 */
	public function addBodyClassesFromString($string) {
	    $classes = $this->getClassesFromString($string);
	    foreach ($classes as $class) {
	        $this->addBodyClass($class);
	    }
	}
	
	/**
	 * @param string $class
	 */
	public function addBodyClass($class) {
	    if (!$this->hasBodyClass($class)) {
	        $this->bodyClasses[] =$class;
	    }
	}
	
	/**
	 * @param string[] $class
	 */
	public function addBodyClasses($classes) {
	    if (!is_array($classes)) {
	        foreach ($classes as $class) {
	            $this->addBodyClass($class);
	        }
	    }
	}
	
	/**
	 * @param string $class
	 * @return bool
	 */
	public function hasBodyClass($class) {
	    return in_array($class, $this->bodyClasses);
	}
	
	/**
	 * @param string $class
	 */
	public function removeBodyClass($class) {
	    $this->bodyClasses = App::getArrayTools()->removeValue($this->bodyClasses, $class);
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function addBodyAttribute($name, $value) {
	    $this->setBodyAttribute($name, $value);
	}
	
	/**
	 * @param array $attributes
	 */
	public function addBodyAttributes($attributes) {
	    if (!is_array($attributes)) {
	        foreach ($attributes as $name => $value) {
	            $this->addBodyAttribute($name, $value);
	        }
	    }
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function setBodyAttribute($name, $value) {
	    $this->bodyAttributes[$name] = $value;
	}
	
	/**
	 * @param string $name
	 */
	public function removeBodyAttribute($name) {
	    if($this->hasBodyAttribute($name)){
	        unset($this->bodyAttributes[$name]);
	    }
	}
	
	/**
	 * @param string $name
	 * @return mixed
	 */
	public function getBodyAttribute($name) {
	    return $this->hasBodyAttribute($name) ? $this->bodyAttributes[$name] : null;
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasBodyAttribute($name) {
	    return array_key_exists($name, $this->bodyAttributes);
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function addBodyStyleAttribute($name, $value) {
	    $this->setBodyStyleAttribute($name, $value);
	}
	
	/**
	 * @param array $attributes
	 */
	public function addBodyStyleAttributes($attributes) {
	    if (!is_array($attributes)) {
	        foreach ($attributes as $name => $value) {
	            $this->addBodyStyleAttribute($name, $value);
	        }
	    }
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 */
	public function setBodyStyleAttribute($name, $value) {
	    $this->bodyStyleAttributes[$name] = $value;
	}
	
	/**
	 * @param string $name
	 */
	public function removeBodyStyleAttribute($name) {
	    if($this->hasBodyStyleAttribute($name)){
	        unset($this->bodyStyleAttributes[$name]);
	    }
	}
	
	/**
	 * @param string $name
	 * @return mixed
	 */
	public function getBodyStyleAttribute($name) {
	    return $this->hasBodyStyleAttribute($name) ? $this->bodyStyleAttributes[$name] : null;
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasBodyStyleAttribute($name) {
	    return array_key_exists($name, $this->bodyStyleAttributes);
	}
	
	/**
	 * @return array
	 */
	protected function getBodyOtherAttributes() {
	    $result = array();
	    if(!empty($this->bodyId)){
	        $result['id'] = $this->bodyId;
	    }
	    return $result;
	}
	
	/**
	 * @return array
	 */
	protected function getBodyOtherStyleAttributes(){
	    $result = array();
	    if(!$this->bodyVisible){
	        $result['display'] = 'none';
	    }
	    return $result;
	}
	
	/**
	 * @return array
	 */
	protected function getBodyOtherClasses(){
	    return array();
	}
	
	/**
	 * @param string $stringClasses
	 * @param array $newStyleAttributes
	 * @param array $newAttributes
	 * @param string[] $excludedAttributes
	 * @param string[] $excludedStyleAttributes
	 * @param string[] $excludedClasses
	 * @param bool $addSpace
	 * @return string
	 */
	public function drawAllBodyAttributes($stringClasses = null, $newStyleAttributes = null, $newAttributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null, $addSpace = true) {
	    $result = '';
	    if(!is_array($excludedAttributes) || !in_array('class', $excludedAttributes)){
	        $result .= $this->drawAllBodyClasses(false, true, $stringClasses, $excludedClasses);
	    }
	    
	    $result = $this->concatTwoStrings($result, $this->drawBodyAttributes(false, $newAttributes, $excludedAttributes));
	    if(!is_array($excludedAttributes) || !in_array('style', $excludedAttributes)){
	        $result = $this->concatTwoStrings($result, $this->drawBodyAllStyleAttributes(true, false, $newStyleAttributes, $excludedStyleAttributes));
	    }
	    return $this->getStringLeftWithSpace($result, $addSpace);
	}
	
	/**
	 * @param bool $addSpace
	 * @param bool $addClassAttribute
	 * @param string $stringClasses
	 * @param string[] $excludedClasses
	 * @return string
	 */
	public function drawAllBodyClasses($addSpace = true, $addClassAttribute = false, $stringClasses = null, $excludedClasses = null){
	    return $this->drawClassesFromMultipleArray(array($this->getBodyOtherClasses(), $this->bodyClasses), null, $addSpace, $addClassAttribute, $stringClasses, $excludedClasses);
	}
	
	/**
	 * @param bool $addSpace
	 * @param array $newAttributes
	 * @param array $excludedAttributes
	 * @return string
	 */
	public function drawBodyAttributes($addSpace = true, $newAttributes = null, $excludedAttributes = null) {
	    $allAttributes = array();
	    $otherAttributes = $this->getBodyOtherAttributes();
	    if(is_array($otherAttributes)){
	        $allAttributes = array_merge($allAttributes, $otherAttributes);
	    }
	    if(is_array($newAttributes)){
	        $allAttributes = array_merge($allAttributes, $newAttributes);
	    }
	    $allAttributes = array_merge($allAttributes, $this->bodyAttributes);
	    return $this->drawAttributesFromList($allAttributes, $addSpace, $excludedAttributes);
	}
	
	/**
	 * @param bool $addStyleAttribute
	 * @param bool $addSpace
	 * @param array $newStyleAttributes
	 * @param string[] $excludedStyleAttributes
	 * @return string
	 */
	public function drawBodyAllStyleAttributes($addStyleAttribute = true, $addSpace = false, $newStyleAttributes = null, $excludedStyleAttributes = null) {
	    $allStyleAttributes = array();
	    $otherStyleAttributes = $this->getBodyOtherStyleAttributes();
	    if(is_array($otherStyleAttributes)){
	        $allStyleAttributes = array_merge($allStyleAttributes, $otherStyleAttributes);
	    }
	    if(is_array($newStyleAttributes)){
	        $allStyleAttributes = array_merge($allStyleAttributes, $newStyleAttributes);
	    }
	    $allStyleAttributes = array_merge($allStyleAttributes, $this->bodyStyleAttributes);
	    return $this->drawStyleAttributeFromList($allStyleAttributes, $addStyleAttribute, $addSpace, $excludedStyleAttributes);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlElement::getOtherAttributes()
	 */
	protected function getOtherAttributes(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
	    $attributes = parent::getOtherAttributes($globalConfig, $callerConfig);
	    if(!empty($this->langIso)){
	        $attributes['lang'] = $this->langIso;
	    }
	    return $attributes;
	}
	
	/**
	 * @return bool
	 */
	public function hasMainContent(){
	    return $this->hasAreaCreator() ? $this->areaCreator->hasContentCreator(Names::MAIN_CONTENT) : false;
	}
	
	/**
	 * @return \muuska\html\HtmlContent
	 */
	public function getMainContent(){
	    return $this->hasAreaCreator() ? $this->areaCreator->createContentByName(Names::MAIN_CONTENT) : null;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function drawMainContent(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    return $this->renderContent($this->getMainContent(), $globalConfig, $callerConfig, Names::MAIN_CONTENT, $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @return string
	 */
	public function drawHeadAssets(\muuska\html\config\HtmlGlobalConfig $globalConfig){
	    return $globalConfig->hasAssetSetter() ? $globalConfig->getAssetSetter()->drawAssets(LocationInPage::HEAD, $globalConfig->getAssetOutputConfig()) : '';
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @return string
	 */
	public function drawFooterAssets(\muuska\html\config\HtmlGlobalConfig $globalConfig){
	    return $globalConfig->hasAssetSetter() ? $globalConfig->getAssetSetter()->drawAssets(LocationInPage::BEFORE_BODY_END, $globalConfig->getAssetOutputConfig()) : '';
	}
	
	/**
	 * @return \muuska\html\HtmlContent[]
	 */
	public function getAlerts(){
	    return $this->hasAreaCreator() ? $this->areaCreator->createContentsByPosition(Names::ALERT_POSITION) : array();
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function drawAlerts(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    return $this->renderContentList($this->getAlerts(), $globalConfig, $callerConfig, 'alerts', $prefix, $suffix, $currentCallerConfig);
	}
    
	/**
	 * @return \muuska\html\HtmlContent
	 */
	public function getLogo(){
	    return $this->getContentByName('logo');
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderLogo(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    return $this->renderContent($this->getLogo(), $globalConfig, $callerConfig, 'logo', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @return boolean
	 */
	public function hasMainNav(){
	    return $this->hasContent(Names::MAIN_NAV);
	}
	
	/**
	 * @return \muuska\html\HtmlContent
	 */
	public function getMainNav(){
	    return $this->getContentByName(Names::MAIN_NAV);
	}
	
	/**
	 * @return boolean
	 */
	public function hasInnerNav(){
	    return $this->hasContent(Names::INNER_NAV);
	}
	
	/**
	 * @return \muuska\html\HtmlContent
	 */
	public function getInnerNav(){
	    return $this->getContentByName(Names::INNER_NAV);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderMainNav(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    return $this->renderContent($this->getMainNav(), $globalConfig, $callerConfig, Names::MAIN_NAV, $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderInnerNav(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    return $this->renderContent($this->getInnerNav(), $globalConfig, $callerConfig, Names::INNER_NAV, $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @return string
	 */
	public function drawTitle(){
	    return $this->drawString($this->title, '<title>', '</title>');
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param boolean $defaultInnerContentEnabled
	 * @param string $extraContent
	 * @return string
	 */
	public function drawHead(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $defaultInnerContentEnabled = true, $extraContent = ''){
	    $string = '<head><meta charset="utf-8">'.$this->drawTitle() . ($defaultInnerContentEnabled ? $this->getDefaultInnerContent() : '') . $extraContent .$this->drawHeadAssets($globalConfig).'</head>';
	    return $string;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $htmlClass
	 * @param string $bodyClass
	 * @param boolean $defaultHeadInnerContentEnabled
	 * @param string $extraHeadContent
	 * @param boolean $html5PrefixEnabled
	 * @return string
	 */
	public function drawPageStart(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $htmlClass = null, $bodyClass = null, $defaultHeadInnerContentEnabled = true, $extraHeadContent = '', $html5PrefixEnabled = true){
	    return ($html5PrefixEnabled ? '<!DOCTYPE html>' : '') . $this->drawStartTag('html', $globalConfig, $callerConfig, $htmlClass) . $this->drawHead($globalConfig, $callerConfig, $defaultHeadInnerContentEnabled, $extraHeadContent) . '<body'.$this->drawAllBodyAttributes($bodyClass).'>';
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return string
	 */
	public function drawPageEnd(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig){
	    return $this->drawFooterAssets($globalConfig) . '</body>' .$this->drawEndTag('html', $globalConfig, $callerConfig);
	}
	
	/**
	 * @return string
	 */
	public function getDefaultInnerContent(){
	    return '<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" /><meta name="apple-mobile-web-app-capable" content="yes" />';
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
    public function getLangIso()
    {
        return $this->langIso;
    }

    /**
     * @return string
     */
    public function getBodyId()
    {
        return $this->bodyId;
    }

    /**
     * @return string[]
     */
    public function getBodyClasses()
    {
        return $this->bodyClasses;
    }

    /**
     * @return array
     */
    public function getBodyAttributes()
    {
        return $this->bodyAttributes;
    }

    /**
     * @return boolean
     */
    public function isBodyVisible()
    {
        return $this->bodyVisible;
    }

    /**
     * @return array
     */
    public function getBodyStyleAttributes()
    {
        return $this->bodyStyleAttributes;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string $langIso
     */
    public function setLangIso($langIso)
    {
        $this->langIso = $langIso;
    }

    /**
     * @param string $bodyId
     */
    public function setBodyId($bodyId)
    {
        $this->bodyId = $bodyId;
    }

    /**
     * @param string[] $bodyClasses
     */
    public function setBodyClasses($bodyClasses)
    {
        $this->bodyClasses = array();
        $this->addBodyClasses($bodyClasses);
    }

    /**
     * @param array $bodyAttributes
     */
    public function setBodyAttributes($bodyAttributes)
    {
        $this->bodyAttributes = array();
        $this->addBodyAttributes($bodyAttributes);
    }

    /**
     * @param boolean $bodyVisible
     */
    public function setBodyVisible($bodyVisible)
    {
        $this->bodyVisible = $bodyVisible;
    }

    /**
     * @param array $bodyStyleAttributes
     */
    public function setBodyStyleAttributes($bodyStyleAttributes)
    {
        $this->bodyStyleAttributes = array();
        $this->addBodyStyleAttributes($bodyStyleAttributes);
    }
}