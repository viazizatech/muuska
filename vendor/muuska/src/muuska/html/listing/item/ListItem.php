<?php
namespace muuska\html\listing\item;
use muuska\html\HtmlElement;
use muuska\html\config\caller\ListItemCallerConfig;
class ListItem extends HtmlElement{
	/**
	 * @var mixed
	 */
	protected $data;
	
	/**
	 * @var string[]
	 */
	protected $disabledFields = array();
	
	/**
	 * @var string[]
	 */
	protected $disabledActions = array();
	
	/**
	 * @param mixed $data
	 * @param string $componentName
	 */
	public function __construct($data, $componentName) {
	    $this->setData($data);
	    $this->componentName = $componentName;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::renderStatic()
	 */
	public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    $result = $this->drawStartTag('li', $globalConfig, $callerConfig, 'item');
	    $list = $this->getList($globalConfig, $callerConfig);
	    if ($list !== null) {
	        $result = $list->renderStaticItem($this, $globalConfig, $callerConfig);
	    }
	    return $result;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return boolean
	 */
	public function hasActions(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig) {
	    return !empty($this->getFinalActions($globalConfig, $callerConfig));
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return \muuska\html\listing\AbstractList
	 */
	public function getList(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig) {
	    $list = null;
	    if(($callerConfig !== null) && ($callerConfig instanceof ListItemCallerConfig)){
	        $list = $callerConfig->getList();
	    }
	    return $list;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return \muuska\html\listing\item\ItemActionCreator[]
	 */
	public function getFinalActions(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig) {
	    $result = array();
	    $list = $this->getList($globalConfig, $callerConfig);
	    if($list !== null){
	        $result = $list->getActions();
	        
	        if(!empty($this->disabledActions)){
	            $oldResult = $result;
	            $result = array();
	            foreach ($oldResult as $key => $action) {
	                if (!in_array($key, $this->disabledActions)) {
	                    $result[$key] = $action;
	                }
	            }
	        }
	    }
	    return $result;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return boolean
	 */
	public function needActionsBlock(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig) {
	    $result = false;
	    $list = $this->getList($globalConfig, $callerConfig);
	    if($list !== null){
	        $result = $list->needActionsBlock();
	    }
	    return $result;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return boolean
	 */
	public function needItemSelector(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig) {
	    $result = false;
	    $list = $this->getList($globalConfig, $callerConfig);
	    if($list !== null){
	        $result = $list->needItemSelector();
	    }
	    return $result;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param number $defaultCount
	 * @return array
	 */
	public function separateActions(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $defaultCount = 1) {
	    $result = array('default' => array(), 'others' => array());
	    $defaultAdded = 0;
	    $finalActions = $this->getFinalActions($globalConfig, $callerConfig);
	    foreach ($finalActions as $action) {
	        if($defaultAdded < $defaultCount){
	            $result['default'][] = $action;
	            $defaultAdded ++;
	        }else {
	            $result['others'][] = $action;
	        }
	    }
	    return $result;
	}
	
	/**
	 * @param \muuska\html\HtmlElement $defaultLink
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @param string[] $stringClasses
	 * @param array $newStyleAttributes
	 * @param array $newAttributes
	 * @param string[] $excludedAttributes
	 * @param string[] $excludedStyleAttributes
	 * @param string[] $excludedClasses
	 * @return string
	 */
	public function drawDefaultLinkStartTag(?\muuska\html\HtmlElement $defaultLink, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null, $stringClasses = null, $newStyleAttributes = null, $newAttributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null) {
	    $content = '';
	    if($defaultLink !== null){
	        $content = $defaultLink->drawStartTag('a', $globalConfig, $currentCallerConfig, $stringClasses, $newStyleAttributes, $newAttributes, $excludedAttributes, $excludedStyleAttributes, $excludedClasses);
	    }
	    return $content;
	}
	
	/**
	 * @param \muuska\html\HtmlElement $defaultLink
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function drawDefaultLinkEndTag(?\muuska\html\HtmlElement $defaultLink, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null) {
	    $content = '';
	    if($defaultLink !== null){
	        $content = $defaultLink->drawEndTag('a', $globalConfig, $currentCallerConfig);
	    }
	    return $content;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @param array $urlParams
	 * @param string $anchor
	 * @param int $mode
	 * @return string
	 */
	public function renderDefaultLink(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null, $urlParams = array(), $anchor = '', $mode = null) {
	    return $this->renderContent($this->getDefaultLink($globalConfig, $callerConfig, $urlParams, $anchor, $mode), $globalConfig, $callerConfig, 'defaultAction', $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param array $urlParams
	 * @param string $anchor
	 * @param int $mode
	 * @return \muuska\html\HtmlContent
	 */
	public function getDefaultLink(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $urlParams = array(), $anchor = '', $mode = null) {
	    $action = $this->getDefaultAction($globalConfig, $callerConfig);
	    $link = null;
	    if($action !== null){
	        $link = $this->createActionLink($action, $urlParams, $anchor, $mode);
	    }
	    return $link;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return \muuska\html\listing\item\ItemActionCreator
	 */
	public function getDefaultAction(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig) {
	    $finalActions = $this->getFinalActions($globalConfig, $callerConfig);
	    $action = null;
	    if(isset($finalActions['default'])){
	        $action = $finalActions['default'];
	    }
	    return $action;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return bool
	 */
	public function hasDefaultAction(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig) {
	    $finalActions = $this->getFinalActions($globalConfig, $callerConfig);
	    return isset($finalActions['default']);
	}
	
	
	/**
	 * @param \muuska\html\listing\item\ItemActionCreator $action
	 * @param array $urlParams
	 * @param string $anchor
	 * @param int $mode
	 * @return \muuska\html\HtmlContent
	 */
	public function createActionLink(\muuska\html\listing\item\ItemActionCreator $action, $urlParams = array(), $anchor = '', $mode = null) {
	    return $action->createAction($this->data, $this, $urlParams, $anchor, $mode);
	}
	
	/**
	 * @param \muuska\html\listing\item\ItemActionCreator $action
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $areaName
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @param array $urlParams
	 * @param string $anchor
	 * @param int $mode
	 * @return string
	 */
	public function renderAction(\muuska\html\listing\item\ItemActionCreator $action, \muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $areaName = 'action', $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null, $urlParams = array(), $anchor = '', $mode = null) {
	    return $this->renderContent($this->createActionLink($action, $urlParams, $anchor, $mode), $globalConfig, $callerConfig, $areaName, $prefix, $suffix, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\listing\ListField $field
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderFieldValue(?\muuska\html\listing\ListField $field, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null)
	{
	    $content = '';
	    if($field !== null){
	        $content = $field->renderValue($this->data, $globalConfig, $currentCallerConfig);
	    }
	    return $content;
	}
	
	/**
	 * @param string $fieldName
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @return string
	 */
	public function renderFieldValueByName($fieldName, \muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null)
	{
	    return $this->renderFieldValue($this->getFieldByName($fieldName, $globalConfig, $callerConfig), $globalConfig, $currentCallerConfig);
	}
	
	/**
	 * @param \muuska\html\listing\ListField $field
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param string $tag
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @param string $stringClasses
	 * @param array $newStyleAttributes
	 * @param array $newAttributes
	 * @param string[] $excludedAttributes
	 * @param string[] $excludedStyleAttributes
	 * @param string[] $excludedClasses
	 * @return string
	 */
	public function renderField(?\muuska\html\listing\ListField $field, \muuska\html\config\HtmlGlobalConfig $globalConfig, $tag, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null, $stringClasses = null, $newStyleAttributes = null, $newAttributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null)
	{
	    $content = '';
	    if($field !== null){
	        $content = $field->drawStartTag($tag, $globalConfig, $currentCallerConfig, $stringClasses, $newStyleAttributes, $newAttributes, $excludedAttributes, $excludedStyleAttributes, $excludedClasses).$this->renderFieldValue($field, $globalConfig, $currentCallerConfig).$field->drawEndTag($tag, $globalConfig, $currentCallerConfig);
	    }
	    return $content;
	}
	
	/**
	 * @param string $fieldName
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $tag
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @param string $stringClasses
	 * @param array $newStyleAttributes
	 * @param array $newAttributes
	 * @param string[] $excludedAttributes
	 * @param string[] $excludedStyleAttributes
	 * @param string[] $excludedClasses
	 * @return string
	 */
	public function renderFieldByName($fieldName, \muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $tag, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null, $stringClasses = null, $newStyleAttributes = null, $newAttributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null)
	{
	    $field = $this->getFieldByName($fieldName, $globalConfig, $callerConfig);
	    return ($field !== null) ? $this->renderField($field, $globalConfig, $tag, $currentCallerConfig, $stringClasses, $newStyleAttributes, $newAttributes, $excludedAttributes, $excludedStyleAttributes, $excludedClasses) : '';
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $tag
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @param string $stringClasses
	 * @param array $newStyleAttributes
	 * @param array $newAttributes
	 * @param string[] $excludedAttributes
	 * @param string[] $excludedStyleAttributes
	 * @param string[] $excludedClasses
	 * @return string
	 */
	public function renderTitle(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $tag = 'span', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null, $stringClasses = null, $newStyleAttributes = null, $newAttributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null)
	{
	    return $this->renderFieldByName('title', $globalConfig, $callerConfig, $tag, $currentCallerConfig, $this->concatTwoStrings('title', $stringClasses), $newStyleAttributes, $newAttributes, $excludedAttributes, $excludedStyleAttributes, $excludedClasses);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $tag
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @param string $stringClasses
	 * @param array $newStyleAttributes
	 * @param array $newAttributes
	 * @param string[] $excludedAttributes
	 * @param string[] $excludedStyleAttributes
	 * @param string[] $excludedClasses
	 * @return string
	 */
	public function renderSubTitle(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $tag = 'span', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null, $stringClasses = null, $newStyleAttributes = null, $newAttributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null)
	{
	    return $this->renderFieldByName('subTitle', $globalConfig, $callerConfig, $tag, $currentCallerConfig, $this->concatTwoStrings('sub_title', $stringClasses), $newStyleAttributes, $newAttributes, $excludedAttributes, $excludedStyleAttributes, $excludedClasses);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $tag
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @param string $stringClasses
	 * @param array $newStyleAttributes
	 * @param array $newAttributes
	 * @param string[] $excludedAttributes
	 * @param string[] $excludedStyleAttributes
	 * @param string[] $excludedClasses
	 * @return string
	 */
	public function renderDescription(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $tag = 'span', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null, $stringClasses = null, $newStyleAttributes = null, $newAttributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null)
	{
	    return $this->renderFieldByName('description', $globalConfig, $callerConfig, $tag, $currentCallerConfig, $this->concatTwoStrings('description', $stringClasses), $newStyleAttributes, $newAttributes, $excludedAttributes, $excludedStyleAttributes, $excludedClasses);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $tag
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @param string $stringClasses
	 * @param array $newStyleAttributes
	 * @param array $newAttributes
	 * @param string[] $excludedAttributes
	 * @param string[] $excludedStyleAttributes
	 * @param string[] $excludedClasses
	 * @return string
	 */
	public function renderSubImage(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $tag = 'span', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null, $stringClasses = null, $newStyleAttributes = null, $newAttributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null)
	{
	    return $this->renderFieldByName('subImage', $globalConfig, $callerConfig, $tag, $currentCallerConfig, $this->concatTwoStrings('sub_image', $stringClasses), $newStyleAttributes, $newAttributes, $excludedAttributes, $excludedStyleAttributes, $excludedClasses);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $tag
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @param string $stringClasses
	 * @param array $newStyleAttributes
	 * @param array $newAttributes
	 * @param string[] $excludedAttributes
	 * @param string[] $excludedStyleAttributes
	 * @param string[] $excludedClasses
	 * @return string
	 */
	public function renderIcon(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $tag = 'span', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null, $stringClasses = null, $newStyleAttributes = null, $newAttributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null)
	{
	    return $this->renderFieldByName('icon', $globalConfig, $callerConfig, $tag, $currentCallerConfig, $this->concatTwoStrings('icon', $stringClasses), $newStyleAttributes, $newAttributes, $excludedAttributes, $excludedStyleAttributes, $excludedClasses);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $tag
	 * @param \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig
	 * @param string $stringClasses
	 * @param array $newStyleAttributes
	 * @param array $newAttributes
	 * @param string[] $excludedAttributes
	 * @param string[] $excludedStyleAttributes
	 * @param string[] $excludedClasses
	 * @return string
	 */
	public function renderImage(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $tag = 'span', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null, $stringClasses = null, $newStyleAttributes = null, $newAttributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null)
	{
	    return $this->renderFieldByName('image', $globalConfig, $callerConfig, $tag, $currentCallerConfig, $this->concatTwoStrings('image', $stringClasses), $newStyleAttributes, $newAttributes, $excludedAttributes, $excludedStyleAttributes, $excludedClasses);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param boolean $appendDefaultLink
	 * @param boolean $addItemTag
	 * @param string $itemTag
	 * @return string
	 */
	public function renderAllPresentationFields(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $appendDefaultLink = true, $addItemTag = true, $itemTag = 'li')
	{
	    $result = $this->renderIcon($globalConfig, $callerConfig).$this->renderImage($globalConfig, $callerConfig).$this->renderSubImage($globalConfig, $callerConfig).$this->renderTitle($globalConfig, $callerConfig).$this->renderSubTitle($globalConfig, $callerConfig).$this->renderDescription($globalConfig, $callerConfig).($appendDefaultLink ? $this->renderDefaultLink($globalConfig, $callerConfig) : '');
	    if($addItemTag){
	        $this->getFullString($globalConfig, $callerConfig, $result, null, $itemTag);
	    }
	    return $result;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $defaultLinkCallerConfig
	 * @param string $defaultLinkClass
	 * @param array $urlParams
	 * @param string $anchor
	 * @param int $mode
	 * @param boolean $addItemTag
	 * @param string $itemTag
	 * @return string
	 */
	public function renderFullyClickablePresentation(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, \muuska\html\config\caller\HtmlCallerConfig $defaultLinkCallerConfig = null, $defaultLinkClass = null, $urlParams = array(), $anchor = '', $mode = null, $addItemTag = true, $itemTag = 'li')
	{
	    $defaultLink = $this->getDefaultLink($globalConfig, $callerConfig);
	    $result = $this->drawDefaultLinkStartTag($defaultLink, $globalConfig, $defaultLinkCallerConfig, $defaultLinkClass) . $this->renderAllPresentationFields($globalConfig, $callerConfig, false).$this->drawDefaultLinkEndTag($defaultLink, $globalConfig, $defaultLinkCallerConfig);
        if($addItemTag){
            $this->getFullString($globalConfig, $callerConfig, $result, null, $itemTag);
        }
        return $result;
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param boolean $addFieldNameToClasses
	 * @param \muuska\html\config\caller\HtmlCallerConfig $fieldCallerConfig
	 * @param string $tag
	 * @return string
	 */
	public function renderAllFields(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', $addFieldNameToClasses = true, \muuska\html\config\caller\HtmlCallerConfig $fieldCallerConfig = null, $tag = 'span')
	{
	    $result = '';
	    $fields = $this->getFinalFields($globalConfig, $callerConfig);
	    foreach ($fields as $field) {
	        $result .= $this->renderField($field, $globalConfig, $tag, $fieldCallerConfig, ($addFieldNameToClasses ? $field->getName() : null));
	    }
	    return $this->drawString($result, $prefix, $suffix);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $prefix
	 * @param string $suffix
	 * @param \muuska\html\config\caller\HtmlCallerConfig $actionCallerConfig
	 * @return string
	 */
	public function renderAllActions(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $actionCallerConfig = null)
	{
	    $result = '';
	    $actions = $this->getFinalActions($globalConfig, $callerConfig);
	    foreach ($actions as $action) {
	        $result .= $this->renderAction($action, $globalConfig, $callerConfig, 'action_'.$action->getName(), '', '', $actionCallerConfig);
	    }
	    return $this->drawString($result, $prefix, $suffix);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @param string $innerString
	 * @param string $customClass
	 * @param string $itemTag
	 * @return string
	 */
	public function getFullString(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $innerString, $customClass = null, $itemTag = 'li')
	{
	    return $this->drawStartTag('li', $globalConfig, $callerConfig, $this->concatTwoStrings('item', $customClass)).$innerString.$this->drawEndTag($itemTag, $globalConfig, $callerConfig);
	}
	
	/**
	 * @param string $fieldName
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return \muuska\html\listing\ListField
	 */
	public function getFieldByName($fieldName, \muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig)
	{
	    $fields = $this->getFinalFields($globalConfig, $callerConfig);
	    return isset($fields[$fieldName]) ? $fields[$fieldName] : null;
	}
	
	/**
	 * @param string $fieldName
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return bool
	 */
	public function hasField($fieldName, \muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig)
	{
	    $fields = $this->getFinalFields($globalConfig, $callerConfig);
	    return isset($fields[$fieldName]);
	}
	
	/**
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 * @return \muuska\html\listing\ListField[]
	 */
	public function getFinalFields(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig)
	{
	    $result = array();
	    $list = $this->getList($globalConfig, $callerConfig);
	    if($list !== null){
	        $result = $list->getFields();
	        if(!empty($this->disabledFields)){
	            $oldResult = $result;
	            $result = array();
	            foreach ($oldResult as $key => $field) {
	                if (!in_array($key, $this->disabledFields)) {
	                    $result[$key] = $field;
	                }
	            }
	        }
	    }
	    return $result;
	}

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
    
    /**
     * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
     * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
     * @return mixed
     */
    public function getDataId(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig)
    {
        $list = $this->getList($globalConfig, $callerConfig);
        return (($list !== null) && $list->hasIdentifierGetter()) ? $list->getIdentifierGetter()->get($this->data) : null;
    }
    
    public function getCheckboxHtml(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig)
    {
        $list = $this->getList($globalConfig, $callerConfig);
        $id = null;
        $checked = false;
        $identifier = 'ids';
        if($list !== null){
            if($list->hasIdentifierGetter()){
                $id = $list->getIdentifierGetter()->get($this->data);
                $selectedIds = $list->getSelectedIds();
                $checked = (is_array($selectedIds) && in_array($id, $selectedIds));
            }
            $identifier = $list->getIdentifier();
        }
        $html = '<input class="checkable_checkbox" type="checkbox" name="'.$identifier.'[]" value="'.$id.'"'.$this->getStringFromCondition($checked, ' checked'). '/>';
        return $html;
    }
    
    public function getRadioHtml(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig)
    {
        $list = $this->getList($globalConfig, $callerConfig);
        $id = null;
        $checked = false;
        $identifier = null;
        if($list !== null){
            if($list->hasIdentifierGetter()){
                $id = $list->getIdentifierGetter()->get($this->data);
                $selectedIds = $list->getSelectedIds();
                $checked = (is_array($selectedIds) && in_array($id, $selectedIds));
            }
            $identifier = $list->getIdentifier();
        }
        $html = '<input class="checkable_radio" type="radio" name="'.$identifier.'" value="'.$id.'"'.$this->getStringFromCondition($checked, ' checked'). '/>';
        return $html;
    }
    
    public function getCheckboxOrRadioHtml(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $checkboxPrefix = '', $checkboxSuffix = '', $radioPrefix = '', $radioSuffix = '')
    {
        $html = '';
        if($this->needItemSelector($globalConfig, $callerConfig)){
            $list = $this->getList($globalConfig, $callerConfig);
            if($list !== null){
                $html = $list->isOnlyOneItemSelectable() ? $radioPrefix.$this->getRadioHtml($globalConfig, $callerConfig).$radioSuffix : $checkboxPrefix.$this->getCheckboxHtml($globalConfig, $callerConfig).$checkboxSuffix;
            }
        }
        return $html;
    }
    
    /**
     * @param string $field
     */
    public function addDisabledField($field) {
        $this->disabledFields[] = $field;
    }
    
    /**
     * @param string $action
     */
    public function addDisabledAction($action) {
        $this->disabledActions[] = $action;
    }
    
    /**
     * @return string[]
     */
    public function getDisabledFields()
    {
        return $this->disabledFields;
    }

    /**
     * @return string[]
     */
    public function getDisabledActions()
    {
        return $this->disabledActions;
    }

    /**
     * @param string[] $disabledFields
     */
    public function setDisabledFields($disabledFields)
    {
        $this->disabledFields = $disabledFields;
    }

    /**
     * @param string[] $disabledActions
     */
    public function setDisabledActions($disabledActions)
    {
        $this->disabledActions = $disabledActions;
    }
}