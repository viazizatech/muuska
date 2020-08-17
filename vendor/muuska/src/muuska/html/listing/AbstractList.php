<?php
namespace muuska\html\listing;
use muuska\html\HtmlElement;
use muuska\html\listing\item\ListItemContainer;
class AbstractList extends HtmlElement implements ListItemContainer{
    /**
     * @var bool
     */
    protected $itemActionEnabled;
	
	/**
	 * @var \muuska\html\listing\item\ItemActionCreator[]
	 */
	protected $actions = array();
	
	/**
	 * @var array
	 */
	protected $data = array();
	
	/**
	 * @var string
	 */
	protected $identifier;
	
	/**
	 * @var \muuska\getter\Getter
	 */
	protected $identifierGetter;
	
	/**
	 * @var \muuska\html\listing\item\ListItemCreator
	 */
	protected $itemCreator;
	
	/**
	 * @var \muuska\renderer\HtmlContentRenderer
	 */
	protected $itemRenderer;
	
	/**
	 * @var ListField[]
	 */
	protected $fields = array();
	
	/**
	 * @var int
	 */
	protected $maxVisibleFields;
	
	/**
	 * @var bool
	 */
	protected $itemSelectorEnabled;
	
	/**
	 * @var string
	 */
	protected $emptyText;
	
	/**
	 * @var string
	 */
	protected $actionText;
	
	/**
	 * @var array
	 */
	protected $selectedIds;
	
	/**
	 * @var bool
	 */
	protected $onlyOneItemSelectable;
	
	/**
	 * @param array $data
	 */
	public function __construct($data = array()) {
	    $this->setData($data);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::renderStatic()
	 */
	public function renderStatic(\muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return $this->drawStartTag('ul', $globalConfig, $callerConfig, $this->componentName) . $this->drawItems($globalConfig, $callerConfig).$this->drawEndTag('ul', $globalConfig, $callerConfig);
	}
	
	/**
	 * @param \muuska\html\listing\item\ListItem $item
	 * @param \muuska\html\config\HtmlGlobalConfig $globalConfig
	 * @param \muuska\html\config\caller\HtmlCallerConfig $callerConfig
	 */
	public function renderStaticItem(\muuska\html\listing\item\ListItem $item, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null) {
	    return $item->getFullString($globalConfig, $callerConfig, $item->renderAllFields($globalConfig, $callerConfig, '<div class="fields">', '</div>').$item->renderAllActions($globalConfig, $callerConfig, '<div class="actions">', '</div>'));
	}
	
	/**
	 * @return boolean
	 */
	public function isEmpty() {
	    return (count($this->data) == 0);
	}
	
	/**
	 * @return boolean
	 */
	public function hasActions() {
	    return !empty($this->actions);
	}
	
	/**
	 * @return boolean
	 */
	public function needItemSelector() {
	    return $this->isItemSelectorEnabled();
	}
	
	/**
	 * @return boolean
	 */
	public function needActionsBlock() {
	    return $this->hasActions();
	}
	
	/**
	 * @return string
	 */
	public function getItemComponentName(){
	    return $this->componentName . '_item';
	}
	
	/**
	 * @param \muuska\html\listing\item\ListItem $item
	 * @param mixed $data
	 * @return \muuska\html\listing\item\ListItem
	 */
	public function formatItem(\muuska\html\listing\item\ListItem $item, $data){
	    if($this->hasItemRenderer()){
	        $item->setRenderer($this->itemRenderer);
	    }
	    if($this->hasIdentifierGetter()){
	        $item->addAttribute('data-id', $this->identifierGetter->get($data));
	    }
	    return $item;
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\listing\item\ListItemContainer::createItem()
	 */
	public function createItem($data, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    return $this->hasItemCreator() ? $this->getItemCreator()->createItem($data, $this, $globalConfig, $currentCallerConfig) : $this->defaultCreateItem($data, $globalConfig, $currentCallerConfig);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\listing\item\ListItemContainer::defaultCreateItem()
	 */
	public function defaultCreateItem($data, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    return $this->formatItem($this->htmls()->createListItem($data, $this->getItemComponentName()), $data);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\listing\item\ListItemContainer::drawItems()
	 */
	public function drawItems(\muuska\html\config\HtmlGlobalConfig $globalConfig, ?\muuska\html\config\caller\HtmlCallerConfig $callerConfig, $prefix = '', $suffix = '', \muuska\html\config\caller\HtmlCallerConfig $currentCallerConfig = null){
	    $data = $this->getData();
	    $content = '';
	    if($currentCallerConfig === null){
	        $currentCallerConfig = $this->createCallerConfig();
	    }
	    if (is_iterable($data)) {
	        foreach($data as $value){
	            $item = $this->createItem($value, $globalConfig, $currentCallerConfig);
	            $content .= $item->generate($globalConfig, $currentCallerConfig);
	        }
	    }
	    return $this->drawString($content, $prefix, $suffix);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\HtmlComponent::createCallerConfig()
	 */
	public function createCallerConfig($stringClasses = null, $styleAttributes = null, $attributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null) {
	    return $this->htmls()->createListItemCallerConfig($this, $this, $stringClasses, $styleAttributes, $attributes, $excludedAttributes, $excludedStyleAttributes, $excludedClasses);
	}
	
	/**
	 * @param string $name
	 * @param \muuska\renderer\value\ValueRenderer $valueRenderer
	 * @param string $label
	 * @return \muuska\html\listing\ListField
	 */
	public function createField($name, \muuska\renderer\value\ValueRenderer $valueRenderer = null, $label = null) {
	    $field = $this->htmls()->createListField($name, $valueRenderer, $label);
	    $this->addField($field);
	    return $field;
	}
	
	/**
	 * @param \muuska\renderer\value\ValueRenderer $valueRenderer
	 * @param string $label
	 * @return \muuska\html\listing\ListField
	 */
	public function createTitleField(\muuska\renderer\value\ValueRenderer $valueRenderer = null, $label = null) {
	    return $this->createField('title', $valueRenderer, $label);
	}
	
	/**
	 * @param \muuska\renderer\value\ValueRenderer $valueRenderer
	 * @param string $label
	 * @return \muuska\html\listing\ListField
	 */
	public function createSubTitleField(\muuska\renderer\value\ValueRenderer $valueRenderer = null, $label = null) {
	    return $this->createField('subTitle', $valueRenderer, $label);
	}
	
	/**
	 * @param \muuska\renderer\value\ValueRenderer $valueRenderer
	 * @param string $label
	 * @return \muuska\html\listing\ListField
	 */
	public function createImageField(\muuska\renderer\value\ValueRenderer $valueRenderer = null, $label = null) {
	    return $this->createField('image', $valueRenderer, $label);
	}
	
	/**
	 * @param \muuska\renderer\value\ValueRenderer $valueRenderer
	 * @param string $label
	 * @return \muuska\html\listing\ListField
	 */
	public function createDescriptionField(\muuska\renderer\value\ValueRenderer $valueRenderer = null, $label = null) {
	    return $this->createField('description', $valueRenderer, $label);
	}
	
	/**
	 * @param \muuska\renderer\value\ValueRenderer $valueRenderer
	 * @param string $label
	 * @return \muuska\html\listing\ListField
	 */
	public function createSubImageField(\muuska\renderer\value\ValueRenderer $valueRenderer = null, $label = null) {
	    return $this->createField('subImage', $valueRenderer, $label);
	}
	
	/**
	 * @param \muuska\renderer\value\ValueRenderer $valueRenderer
	 * @param string $label
	 * @return \muuska\html\listing\ListField
	 */
	public function createIconField(\muuska\renderer\value\ValueRenderer $valueRenderer = null, $label = null) {
	    return $this->createField('icon', $valueRenderer, $label);
	}
	
	/**
	 * @param ListField $action
	 */
	public function addField(ListField $field)
	{
	    $this->fields[$field->getName()] = $field;
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasField($name)
	{
	    return isset($this->fields[$name]);
	}
	
	/**
	 * @param string $name
	 */
	public function removeField($name)
	{
	    if($this->hasField($name)){
	        unset($this->fields[$name]);
	    }
	}
	
	/**
	 * @param ListField[] $fields
	 */
	public function addFields($fields)
	{
	    if (is_array($fields)) {
	        foreach ($fields as $field) {
	            $this->addField($field);
	        }
	    }
	}
	
	/**
	 * @return ListField[]
	 */
	public function getFields()
	{
	    return $this->fields;
	}
	
	/**
	 * @param ListField[] $fields
	 */
	public function setFields($fields)
	{
	    $this->fields = array();
	    $this->addFields($fields);
	}
	
	/**
	 * @param string $name
	 * @param \muuska\url\objects\ObjectUrl $urlCreator
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param \muuska\html\HtmlContent $icon
	 * @param string $title
	 * @param boolean $buttonStyleEnabled
	 * @param string $style
	 * @return \muuska\html\listing\item\ItemAction
	 */
	public function createAction($name, \muuska\url\objects\ObjectUrl $urlCreator = null, \muuska\html\HtmlContent $innerContent = null, \muuska\html\HtmlContent $icon = null, $title = '', $buttonStyleEnabled = false, $style = null) {
	    $action = $this->htmls()->createItemAction($name, $urlCreator, $innerContent, $icon, $title, $buttonStyleEnabled, $style);
	    $this->addAction($action);
	    return $action;
	}
	
	/**
	 * @param \muuska\url\objects\ObjectUrl $urlCreator
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param \muuska\html\HtmlContent $icon
	 * @param string $title
	 * @param boolean $buttonStyleEnabled
	 * @param string $style
	 * @return \muuska\html\listing\item\ItemAction
	 */
	public function createDefaultAction(\muuska\url\objects\ObjectUrl $urlCreator = null, \muuska\html\HtmlContent $innerContent = null, \muuska\html\HtmlContent $icon = null, $title = '', $buttonStyleEnabled = false, $style = null) {
	    return $this->createAction('default', $urlCreator, $innerContent, $icon, $title, $buttonStyleEnabled, $style);
	}
	
	/**
	 * @param \muuska\html\listing\item\ItemActionCreator $action
	 */
	public function addAction(\muuska\html\listing\item\ItemActionCreator $action)
	{
	    $this->actions[$action->getName()] = $action;
	}
	
	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasAction($name)
	{
	    return isset($this->actions[$name]);
	}
	
	/**
	 * @param string $name
	 */
	public function removeAction($name)
	{
	    if($this->hasAction($name)){
	        unset($this->actions[$name]);
	    }
	}
	
	/**
	 * @param \muuska\html\listing\item\ItemActionCreator[] $actions
	 */
	public function addActions($actions)
	{
	    if (is_array($actions)) {
	        foreach ($actions as $action) {
	            $this->addAction($action);
	        }
	    }
	}
	
	/**
	 * @return \muuska\html\listing\item\ItemActionCreator[]
	 */
	public function getActions()
	{
	    return $this->actions;
	}
	
	/**
	 * @param \muuska\html\listing\item\ItemActionCreator[] $actions
	 */
	public function setActions($actions)
	{
	    $this->actions = array();
	    $this->addActions($actions);
	}
	
	/**
	 * @param callable $callback
	 * @param array $initialParams
	 */
	public function setItemCreatorFromFunction($callback, $initialParams = null)
	{
	    $this->setItemCreator($this->htmls()->createDefaultListItemCreator($callback, $initialParams));
	}
	
	/**
	 * {@inheritDoc}
	 * @see \muuska\html\listing\item\ListItemContainer::hasItemCreator()
	 */
	public function hasItemCreator()
	{
	    return ($this->itemCreator !== null);
	}
	
	/**
	 * @return boolean
	 */
	public function hasItemRenderer()
	{
	    return ($this->itemRenderer !== null);
	}
	
	/**
	 * @return boolean
	 */
	public function hasIdentifierGetter()
	{
	    return ($this->identifierGetter !== null);
	}
	
	/**
	 * @return boolean
	 */
	public function isAllDataSelected() {
	    return (is_array($this->selectedIds) && (count($this->data) === count($this->selectedIds)));
	}

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @return \muuska\getter\Getter
     */
    public function getIdentifierGetter()
    {
        return $this->identifierGetter;
    }

    /**
     * @return \muuska\html\listing\item\ListItemCreator
     */
    public function getItemCreator()
    {
        return $this->itemCreator;
    }

    /**
     * @return \muuska\renderer\HtmlContentRenderer
     */
    public function getItemRenderer()
    {
        return $this->itemRenderer;
    }

    /**
     * @return int
     */
    public function getMaxVisibleFields()
    {
        return $this->maxVisibleFields;
    }

    /**
     * @return boolean
     */
    public function isItemSelectorEnabled()
    {
        return $this->itemSelectorEnabled;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @param \muuska\getter\Getter $identifierGetter
     */
    public function setIdentifierGetter(?\muuska\getter\Getter $identifierGetter)
    {
        $this->identifierGetter = $identifierGetter;
    }

    /**
     * @param \muuska\html\listing\item\ListItemCreator $itemCreator
     */
    public function setItemCreator(?\muuska\html\listing\item\ListItemCreator $itemCreator)
    {
        $this->itemCreator = $itemCreator;
    }

    /**
     * @param \muuska\renderer\HtmlContentRenderer $itemRenderer
     */
    public function setItemRenderer(?\muuska\renderer\HtmlContentRenderer $itemRenderer)
    {
        $this->itemRenderer = $itemRenderer;
    }

    /**
     * @param int $maxVisibleFields
     */
    public function setMaxVisibleFields($maxVisibleFields)
    {
        $this->maxVisibleFields = $maxVisibleFields;
    }

    /**
     * @param boolean $itemSelectorEnabled
     */
    public function setItemSelectorEnabled($itemSelectorEnabled)
    {
        $this->itemSelectorEnabled = $itemSelectorEnabled;
    }
    
    /**
     * @return boolean
     */
    public function isItemActionEnabled()
    {
        return $this->itemActionEnabled;
    }

    /**
     * @return string
     */
    public function getEmptyText()
    {
        return $this->emptyText;
    }

    /**
     * @return string
     */
    public function getActionText()
    {
        return $this->actionText;
    }

    /**
     * @param boolean $itemActionEnabled
     */
    public function setItemActionEnabled($itemActionEnabled)
    {
        $this->itemActionEnabled = $itemActionEnabled;
    }

    /**
     * @param string $actionText
     */
    public function setActionText($actionText)
    {
        $this->actionText = $actionText;
    }
    
    /**
     * @return array
     */
    public function getSelectedIds()
    {
        return $this->selectedIds;
    }

    /**
     * @param string $emptyText
     */
    public function setEmptyText($emptyText)
    {
        $this->emptyText = $emptyText;
    }

    /**
     * @param array $selectedIds
     */
    public function setSelectedIds($selectedIds)
    {
        $this->selectedIds = $selectedIds;
    }
    
    /**
     * @return boolean
     */
    public function isOnlyOneItemSelectable()
    {
        return $this->onlyOneItemSelectable;
    }

    /**
     * @param boolean $onlyOneItemSelectable
     */
    public function setOnlyOneItemSelectable($onlyOneItemSelectable)
    {
        $this->onlyOneItemSelectable = $onlyOneItemSelectable;
    }
}