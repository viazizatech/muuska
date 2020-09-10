<?php
namespace muuska\instantiator;

class Htmls
{
	private static $instance;
	
	protected function __construct(){}
	
	/**
	 * @return \muuska\instantiator\Htmls
	 */
	public static function getInstance(){
		if(self::$instance === null){
		    self::$instance = new static();
		}
		return self::$instance; 
	}
	
	/**
	 * @param string $title
	 * @param \muuska\html\areacreator\AreaCreator $areaCreator
	 * @param \muuska\renderer\HtmlContentRenderer $renderer
	 * @return \muuska\html\HtmlPage
	 */
	public function createHtmlPage($title, \muuska\html\areacreator\AreaCreator $areaCreator = null, \muuska\renderer\HtmlContentRenderer $renderer = null){
	    return new \muuska\html\HtmlPage($title, $areaCreator, $renderer);
	}
	
	/**
	 * @param \muuska\html\areacreator\AreaCreator $areaCreator
	 * @param \muuska\renderer\HtmlContentRenderer $renderer
	 * @param string $name
	 * @return \muuska\html\HtmlCustomElement
	 */
	public function createHtmlCustomElement(\muuska\html\areacreator\AreaCreator $areaCreator = null, \muuska\renderer\HtmlContentRenderer $renderer = null, $name = null){
	    return new \muuska\html\HtmlCustomElement($areaCreator, $renderer, $name);
	}
	
	/**
	 * @param string $html
	 * @param string $name
	 * @return \muuska\html\HtmlString
	 */
	public function createHtmlString($html, $name = null){
	    return new \muuska\html\HtmlString($html, $name);
	}
	
	/**
	 * @return \muuska\html\areacreator\DefaultAreaCreator
	 */
	public function createDefaultAreaCreator(){
	    return new \muuska\html\areacreator\DefaultAreaCreator();
	}
	
	/**
	 * @param string $lang
	 * @param \muuska\asset\AssetSetter $assetSetter
	 * @param \muuska\util\theme\Theme $theme
	 * @param \muuska\asset\AssetOutputConfig $assetOutputConfig
	 * @return \muuska\html\config\DefaultHtmlGlobalConfig
	 */
	public function createDefaultHtmlGlobalConfig($lang, \muuska\asset\AssetSetter $assetSetter = null, \muuska\util\theme\Theme $theme = null, \muuska\asset\AssetOutputConfig $assetOutputConfig = null){
	    return new \muuska\html\config\DefaultHtmlGlobalConfig($lang, $assetSetter, $theme, $assetOutputConfig);
	}
	
	/**
	 * @param \muuska\html\HtmlContent $callerInstance
     * @param string $stringClasses
     * @param array $styleAttributes
     * @param array $attributes
     * @param string[] $excludedAttributes
     * @param string[] $excludedStyleAttributes
     * @param string[] $excludedClasses
	 * @return \muuska\html\config\caller\DefaultHtmlCallerConfig
	 */
	public function createDefaultHtmlCallerConfig(\muuska\html\HtmlContent $callerInstance, $stringClasses = null, $styleAttributes = null, $attributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null){
	    return new \muuska\html\config\caller\DefaultHtmlCallerConfig($callerInstance, $stringClasses, $styleAttributes, $attributes, $excludedAttributes, $excludedStyleAttributes, $excludedClasses);
	}
	
	/**
	 * @param \muuska\html\listing\AbstractList $list
     * @param \muuska\html\HtmlContent $callerInstance
     * @param string $stringClasses
     * @param array $styleAttributes
     * @param array $attributes
     * @param string[] $excludedAttributes
     * @param string[] $excludedStyleAttributes
     * @param string[] $excludedClasses
	 * @return \muuska\html\config\caller\ListItemCallerConfig
	 */
	public function createListItemCallerConfig(\muuska\html\listing\AbstractList $list, \muuska\html\HtmlContent $callerInstance, $stringClasses = null, $styleAttributes = null, $attributes = null, $excludedAttributes = null, $excludedStyleAttributes = null, $excludedClasses = null){
	    return new \muuska\html\config\caller\ListItemCallerConfig($list, $callerInstance, $stringClasses, $styleAttributes, $attributes, $excludedAttributes, $excludedStyleAttributes, $excludedClasses);
	}
	
	/**
	 * @param string $name
	 * @param string $componentName
	 * @param \muuska\renderer\HtmlContentRenderer $renderer
	 * @return \muuska\html\HtmlElement
	 */
	public function createHtmlElement($name = null, $componentName = null, \muuska\renderer\HtmlContentRenderer $renderer = null){
	    return new \muuska\html\HtmlElement($renderer, $componentName, $renderer);
	}	
	
	/**
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param string $href
	 * @param \muuska\html\HtmlContent $icon
	 * @param string $title
	 * @param boolean $buttonStyleEnabled
	 * @param string $style
	 * @return \muuska\html\command\HtmlLink
	 */
	public function createHtmlLink(\muuska\html\HtmlContent $innerContent = null, $href ='#', \muuska\html\HtmlContent $icon = null, $title = '', $buttonStyleEnabled = false, $style = null){
	    return new \muuska\html\command\HtmlLink($innerContent, $href, $icon, $title, $buttonStyleEnabled, $style);
	}
	
	/**
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param string $type
	 * @param \muuska\html\HtmlContent $icon
	 * @param string $style
	 * @return \muuska\html\command\Button
	 */
	public function createButton(\muuska\html\HtmlContent $innerContent = null, $type = 'button', \muuska\html\HtmlContent $icon = null, $style = null){
	    return new \muuska\html\command\Button($innerContent, $type, $icon, $style);
	}
	
	/**
	 * @param \muuska\html\HtmlContent[] $children
	 * @return \muuska\html\command\ButtonGroup
	 */
	public function createButtonGroup($children = array()){
	    return new \muuska\html\command\ButtonGroup($children);
	}
	
	/**
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param string $name
	 * @param \muuska\renderer\HtmlContentRenderer $renderer
	 * @return \muuska\html\HtmlLabel
	 */
	public function createHtmlLabel(\muuska\html\HtmlContent $innerContent = null, $name = null, \muuska\renderer\HtmlContentRenderer $renderer = null){
	    return new \muuska\html\HtmlLabel($innerContent, $name, $renderer);
	}
	
	/**
	 * @param string $type
	 * @param string $name
	 * @param mixed $value
	 * @param string $placeholder
	 * @return \muuska\html\input\HtmlInput
	 */
	public function createHtmlInput($type, $name, $value = null, $placeholder = null){
	    return new \muuska\html\input\HtmlInput($type, $name, $value, $placeholder);
	}
	
	/**
	 * @param string $url
	 * @param string $name
	 * @param mixed $value
	 * @param string $placeholder
	 * @return \muuska\html\input\Autocomplete
	 */
	public function createAutocomplete($url, $name, $value = null, $placeholder = null){
	    return new \muuska\html\input\Autocomplete($url, $name, $value, $placeholder);
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 * @return \muuska\html\input\InputHidden
	 */
	public function createInputHidden($name, $value = null){
	    return new \muuska\html\input\InputHidden($name, $value);
	}
	
	/**
	 * @param string $name
	 * @param string $label
	 * @param mixed $value
	 * @param boolean $checked
	 * @return \muuska\html\input\Checkbox
	 */
	public function createCheckbox($name, $label = '', $value = null, $checked = false){
	    return new \muuska\html\input\Checkbox($name, $label, $value, $checked);
	}
	
	/**
	 * @param string $name
	 * @param \muuska\option\provider\OptionProvider $optionProvider
	 * @param mixed $value
	 * @return \muuska\html\input\Radio
	 */
	public function createRadio($name, \muuska\option\provider\OptionProvider $optionProvider = null, $value = null){
	    return new \muuska\html\input\Radio($name, $optionProvider, $value);
	}
	
	/**
	 * @param string $name
	 * @param \muuska\option\provider\OptionProvider $optionProvider
	 * @param mixed $value
	 * @return \muuska\html\input\Radio
	 */
	public function createRadioSwitch($name, \muuska\option\provider\OptionProvider $optionProvider = null, $value = null){
	    return new \muuska\html\input\RadioSwitch($name, $optionProvider, $value);
	}
	
	/**
	 * @param string $name
	 * @param \muuska\option\provider\OptionProvider $optionProvider
	 * @param mixed $value
	 * @param boolean $emptyOptionEnabled
	 * @param mixed $emptyOptionValue
	 * @param string $emptyOptionText
	 * @return \muuska\html\input\Select
	 */
	public function createSelect($name, \muuska\option\provider\OptionProvider $optionProvider = null, $value = null, $emptyOptionEnabled = false, $emptyOptionValue = null, $emptyOptionText = null){
	    return new \muuska\html\input\Select($name, $optionProvider, $value, $emptyOptionEnabled, $emptyOptionValue, $emptyOptionText);
	}
	
	/**
	 * @param string $name
	 * @param \muuska\option\provider\OptionProvider $optionProvider
	 * @param mixed $value
	 * @param boolean $emptyOptionEnabled
	 * @param mixed $emptyOptionValue
	 * @param string $emptyOptionText
	 * @return \muuska\html\input\Select2
	 */
	public function createSelect2($name, \muuska\option\provider\OptionProvider $optionProvider = null, $value = null, $emptyOptionEnabled = false, $emptyOptionValue = null, $emptyOptionText = null){
	    return new \muuska\html\input\Select2($name, $optionProvider, $value, $emptyOptionEnabled, $emptyOptionValue, $emptyOptionText);
	}
	
	/**
	 * @param string $name
	 * @param string $value
	 * @param int $rows
	 * @param int $cols
	 * @return \muuska\html\input\Textarea
	 */
	public function createTextarea($name, $value = null, $rows = null, $cols = null){
	    return new \muuska\html\input\Textarea($name, $value, $rows, $cols);
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 * @return \muuska\html\input\RichTextEditor
	 */
	public function createRichTextEditor($name, $value = null){
	    return new \muuska\html\input\RichTextEditor($name, $value);
	}
	
	/**
	 * @param string $uploadUrl
	 * @param boolean $multiple
	 * @param string $accept
	 * @return \muuska\html\input\FileUpload
	 */
	public function createFileUpload($uploadUrl, $multiple = false, $accept = ''){
	    return new \muuska\html\input\FileUpload($uploadUrl, $multiple, $accept);
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 * @param string $filePreview
	 * @param boolean $fileSaved
	 * @param boolean $useAsTemplate
	 * @return \muuska\html\input\UploadPreview
	 */
	public function createUploadPreview($name, $value, $filePreview, $fileSaved = false, $useAsTemplate = false){
	    return new \muuska\html\input\UploadPreview($name, $value, $filePreview, $fileSaved, $useAsTemplate);
	}
	
	/**
	 * @param \muuska\html\HtmlContent $input
	 * @param \muuska\html\HtmlContent $icon
	 * @param int $iconPosition
	 * @return \muuska\html\input\InputIcon
	 */
	public function createInputIcon(\muuska\html\HtmlContent $input = null, \muuska\html\HtmlContent $icon = null, $iconPosition = null){
	    return new \muuska\html\input\InputIcon($input, $icon, $iconPosition);
	}
	
	/**
	 * @param \muuska\html\HtmlContent $input
	 * @return \muuska\html\input\InputGroup
	 */
	public function createInputGroup(\muuska\html\HtmlContent $input = null){
	    return new \muuska\html\input\InputGroup($input);
	}
	
	/**
	 * @param \muuska\html\HtmlContent[] $children
	 * @return \muuska\html\input\CustomInputGroup
	 */
	public function createCustomInputGroup($children = array()){
	    return new \muuska\html\input\CustomInputGroup($children);
	}
	
	/**
	 * @param \muuska\html\HtmlContent $innerConntent
	 * @return \muuska\html\input\InputGroupText
	 */
	public function createInputGroupText(\muuska\html\HtmlContent $innerConntent = null){
	    return new \muuska\html\input\InputGroupText($innerConntent);
	}
	
	/**
	 * @param string $label
	 * @return \muuska\html\ChildrenContainer
	 */
	public function createChildrenContainer($label = ''){
	    return new \muuska\html\ChildrenContainer($label);
	}
	
	/**
	 * @param string $label
	 * @return \muuska\html\Fieldset
	 */
	public function createFieldset($label = ''){
	    return new \muuska\html\Fieldset($label);
	}
	
	/**
	 * @param int $totalResult
	 * @param int $itemsPerPage
	 * @param int $currentPage
	 * @param int $maxPageDisplayed
	 * @param \muuska\url\pagination\PaginationUrl $urlCreator
	 * @return \muuska\html\listing\pagination\Pagination
	 */
	public function createPagination($totalResult, $itemsPerPage = 20, $currentPage = 1, $maxPageDisplayed = 5, \muuska\url\pagination\PaginationUrl $urlCreator = null){
	    return new \muuska\html\listing\pagination\Pagination($totalResult, $itemsPerPage, $currentPage, $maxPageDisplayed, $urlCreator);
	}
	
	/**
	 * @param \muuska\option\provider\OptionProvider $optionProvider
	 * @param mixed $selectedValue
	 * @param \muuska\url\pagination\ListLimiterUrl $urlCreator
	 * @return \muuska\html\listing\ListLimiterSwitcher
	 */
	public function createListLimiterSwitcher(\muuska\option\provider\OptionProvider $optionProvider = null, $selectedValue = null, \muuska\url\pagination\ListLimiterUrl $urlCreator = null){
	    return new \muuska\html\listing\ListLimiterSwitcher($optionProvider, $selectedValue, $urlCreator);
	}
	
	/**
	 * @param string $name
	 * @param \muuska\renderer\value\ValueRenderer $valueRenderer
	 * @param string $label
	 * @return \muuska\html\listing\ListField
	 */
	public function createListField($name, \muuska\renderer\value\ValueRenderer $valueRenderer = null, $label = null){
	    return new \muuska\html\listing\ListField($name, $valueRenderer, $label);
	}
	
	/**
	 * @param mixed $data
	 * @param string $componentName
	 * @return \muuska\html\listing\item\ListItem
	 */
	public function createListItem($data, $componentName){
	    return new \muuska\html\listing\item\ListItem($data, $componentName);
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
	public function createItemAction($name, \muuska\url\objects\ObjectUrl $urlCreator = null, \muuska\html\HtmlContent $innerContent = null, \muuska\html\HtmlContent $icon = null, $title = '', $buttonStyleEnabled = false, $style = null){
	    return new \muuska\html\listing\item\ItemAction($name, $urlCreator, $innerContent, $icon, $title, $buttonStyleEnabled, $style);
	}
	
	/**
	 * @param callable $callback
	 * @param array $initialParams
	 * @return \muuska\html\listing\item\DefaultListItemCreator
	 */
	public function createDefaultListItemCreator($callback, $initialParams = null){
	    return new \muuska\html\listing\item\DefaultListItemCreator($callback, $initialParams);
	}
	
	/**
	 * @param string $name
	 * @param callable $callback
	 * @param array $initialParams
	 * @return \muuska\html\listing\item\DefaultListItemCreator
	 */
	public function createDefaultItemActionCreator($name, $callback, $initialParams = null){
	    return new \muuska\html\listing\item\DefaultItemActionCreator($name, $callback, $initialParams);
	}
	
	/**
	 * @param array $data
	 * @return \muuska\html\listing\DefaultList
	 */
	public function createDefaultList($data = array()){
	    return new \muuska\html\listing\DefaultList($data);
	}
	
	/**
	 * @param array $data
	 * @return \muuska\html\listing\Carousel
	 */
	public function createCarousel($data = array()){
	    return new \muuska\html\listing\Carousel($data);
	}
	
	/**
	 * @param array $data
	 * @return \muuska\html\listing\GridList
	 */
	public function createGridList($data = array()){
	    return new \muuska\html\listing\GridList($data);
	}
	
	/**
	 * @param array $data
	 * @return \muuska\html\listing\FullyClickableList
	 */
	public function createFullyClickableList($data = array()){
	    return new \muuska\html\listing\FullyClickableList($data);
	}
	
	/**
	 * @param array $data
	 * @return \muuska\html\listing\PresentationList
	 */
	public function createPresentationList($data = array()){
	    return new \muuska\html\listing\PresentationList($data);
	}
	
	/**
	 * @param array $data
	 * @return \muuska\html\listing\Accordion
	 */
	public function createAccordion($data = array()){
	    return new \muuska\html\listing\Accordion($data);
	}
	
	/**
	 * @param array $data
	 * @return \muuska\html\listing\Masonry
	 */
	public function createMasonry($data = array()){
	    return new \muuska\html\listing\Masonry($data);
	}
	
	/**
	 * @param array $data
	 * @return \muuska\html\listing\Masonry
	 */
	public function createMenuList($data = array()){
	    return new \muuska\html\listing\tree\MenuList($data);
	}
	
	/**
	 * @param array $data
	 * @return \muuska\html\listing\table\Table
	 */
	public function createTable($data = array()){
	    return new \muuska\html\listing\table\Table($data);
	}
	
	/**
	 * @param string $name
	 * @param \muuska\renderer\value\ValueRenderer $valueRenderer
	 * @param string $label
	 * @return \muuska\html\listing\table\Column
	 */
	public function createColumn($name, \muuska\renderer\value\ValueRenderer $valueRenderer = null, $label = null){
	    return new \muuska\html\listing\table\Column($name, $valueRenderer, $label);
	}
	
	/**
	 * @param array $data
	 * @return \muuska\html\listing\tree\HtmlTree
	 */
	public function createHtmlTree($data = array()){
	    return new \muuska\html\listing\tree\HtmlTree($data);
	}
	
	/**
	 * @param string $data
	 * @param string $componentName
	 * @return \muuska\html\listing\tree\TreeItem
	 */
	public function createTreeItem($data, $componentName){
	    return new \muuska\html\listing\tree\TreeItem($data, $componentName);
	}
	
	/**
	 * @param array $data
	 * @return \muuska\html\listing\Picto
	 */
	public function createPicto($data = array()){
	    return new \muuska\html\listing\Picto($data);
	}
	
	/**
	 * @param string $action
	 * @return \muuska\html\form\Form
	 */
	public function createForm($action = ''){
	    return new \muuska\html\form\Form($action);
	}
	
	/**
	 * @param string $action
	 * @param \muuska\html\HtmlContent $input
	 * @param boolean $iconEnabled
	 * @param int $iconPosition
	 * @return \muuska\html\form\QuickSearchForm
	 */
	public function createQuickSearchForm($action = '',  \muuska\html\HtmlContent $input = null, $iconEnabled = true, $iconPosition = null){
	    return new \muuska\html\form\QuickSearchForm($action, $input, $iconEnabled, $iconPosition);
	}
	
	/**
	 * @param string $name
	 * @param \muuska\html\HtmlContent $label
	 * @param \muuska\html\HtmlContent $input
	 * @return \muuska\html\form\FormField
	 */
	public function createFormField($name, \muuska\html\HtmlContent $label = null, \muuska\html\HtmlContent $input = null){
	    return new \muuska\html\form\FormField($name, $label, $input);
	}
	
	/**
	 * @param string $name
	 * @param \muuska\html\HtmlContent $label
	 * @param \muuska\html\HtmlContent $input
	 * @return \muuska\html\form\GridFormField
	 */
	public function createGridFormField($name, \muuska\html\HtmlContent $label = null, \muuska\html\HtmlContent $input = null){
	    return new \muuska\html\form\GridFormField($name, $label, $input);
	}
	
	/**
	 * @param string $name
	 * @param string $label
	 * @param string $activeLang
	 * @return \muuska\html\form\TranslatableFormField
	 */
	public function createTranslatableFormField($name, $label, $activeLang){
	    return new \muuska\html\form\TranslatableFormField($name, $label, $activeLang);
	}
	
	/**
	 * @param string $name
	 * @param string $label
	 * @param string $activeLang
	 * @return \muuska\html\form\GridTranslatableField
	 */
	public function createGridTranslatableField($name, $label, $activeLang){
	    return new \muuska\html\form\GridTranslatableField($name, $label, $activeLang);
	}
	
	/**
	 * @param string $src
	 * @param string $alt
	 * @param string $title
	 * @return \muuska\html\HtmlImage
	 */
	public function createHtmlImage($src, $alt = '', $title = ''){
	    return new \muuska\html\HtmlImage($src, $alt, $title);
	}
	
	/**
	 * @param \muuska\asset\RelativeAssetResolver $relativeAssetResolver
	 * @param string $location
	 * @param string $alt
	 * @param string $title
	 * @param string $library
	 * @return \muuska\html\RelativeHtmlImage
	 */
	public function createRelativeHtmlImage(\muuska\asset\RelativeAssetResolver $relativeAssetResolver, $location, $alt = '', $title = '', $library = null){
	    return new \muuska\html\RelativeHtmlImage($relativeAssetResolver, $location, $alt, $title, $library);
	}
	
	/**
	 * @param \muuska\html\HtmlImage $mainImage
	 * @param string $title
	 * @param string $subTitle
	 * @param \muuska\html\HtmlContent $mainLink
	 * @param string $backgroundImageUrl
	 * @return \muuska\html\Banner
	 */
	public function createBanner(\muuska\html\HtmlImage $mainImage, $title = null, $subTitle = null, \muuska\html\HtmlContent $mainLink = null, $backgroundImageUrl = null){
	    return new \muuska\html\Banner($mainImage, $title, $subTitle, $mainLink, $backgroundImageUrl);
	}
	
	/**
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param \muuska\html\HtmlContent $icon
	 * @return \muuska\html\dropdown\Dropdown
	 */
	public function createDropdown(\muuska\html\HtmlContent $innerContent = null, \muuska\html\HtmlContent $icon = null){
	    return new \muuska\html\dropdown\Dropdown($innerContent, $icon);
	}
	
	/**
	 * @param \muuska\html\HtmlContent $defaultContent
	 * @return \muuska\html\dropdown\SplitDropdown
	 */
	public function createSplitDropdown(\muuska\html\HtmlContent $defaultContent = null){
	    return new \muuska\html\dropdown\SplitDropdown($defaultContent);
	}
	
	/**
	 * @param \muuska\html\HtmlContent $dropdownToggle
	 * @return \muuska\html\dropdown\CustomDropdown
	 */
	public function createCustomDropdown(\muuska\html\HtmlContent $dropdownToggle = null){
	    return new \muuska\html\dropdown\CustomDropdown($dropdownToggle);
	}
	
	/**
	 * @param string $type
	 * @param array $alerts
	 * @param boolean $closeButtonEnabled
	 * @param string $title
	 * @param \muuska\html\HtmlContent $icon
	 * @return \muuska\html\alert\HtmlAlert
	 */
	public function createHtmlAlert($type, $alerts = array(), $closeButtonEnabled = false, $title = null, \muuska\html\HtmlContent $icon = null){
	    return new \muuska\html\alert\HtmlAlert($type, $alerts, $closeButtonEnabled, $title, $icon);
	}
	
	/**
	 * @param string $value
	 * @return \muuska\html\icon\ClassIcon
	 */
	public function createClassIcon($value){
	    return new \muuska\html\icon\ClassIcon($value);
	}
	
	/**
	 * @param \muuska\html\nav\HtmlNav $nav
	 * @return \muuska\html\nav\FullNavigation
	 */
	public function createFullNavigation(\muuska\html\nav\HtmlNav $nav = null){
	    return new \muuska\html\nav\FullNavigation($nav);
	}
	
	/**
	 * @return \muuska\html\nav\HtmlNav
	 */
	public function createHtmlNav(){
	    return new \muuska\html\nav\HtmlNav();
	}
	
	/**
	 * @param string $name
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param string $href
	 * @param \muuska\html\HtmlContent $icon
	 * @param string $title
	 * @return \muuska\html\nav\HtmlNavItem
	 */
	public function createHtmlNavItem($name, \muuska\html\HtmlContent $innerContent, $href ='#', \muuska\html\HtmlContent $icon = null, $title = ''){
	    return new \muuska\html\nav\HtmlNavItem($name, $innerContent, $href, $icon, $title);
	}
	
	/**
	 * @param string $title
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param \muuska\html\HtmlContent $icon
	 * @return \muuska\html\panel\Panel
	 */
	public function createPanel($title = null, \muuska\html\HtmlContent $innerContent = null, \muuska\html\HtmlContent $icon = null){
	    return new \muuska\html\panel\Panel($title, $innerContent, $icon);
	}
	
	/**
	 * @param string $title
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param \muuska\html\HtmlContent $icon
	 * @return \muuska\html\panel\ListPanel
	 */
	public function createListPanel($title = null, \muuska\html\HtmlContent $innerContent = null, \muuska\html\HtmlContent $icon = null){
	    return new \muuska\html\panel\ListPanel($title, $innerContent, $icon);
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 * @param string $htmlValue
	 * @param \muuska\html\HtmlContent $label
	 * @param \muuska\renderer\value\ValueRenderer $valueRenderer
	 * @return \muuska\html\HtmlFieldValue
	 */
	public function createHtmlFieldValue($name, $value, $htmlValue = null, \muuska\html\HtmlContent $label = null, \muuska\renderer\value\ValueRenderer $valueRenderer = null){
	    return new \muuska\html\HtmlFieldValue($name, $value, $htmlValue, $label, $valueRenderer);
	}
	
	/**
	 * @param string $name
	 * @param mixed $value
	 * @param string $htmlValue
	 * @param \muuska\html\HtmlContent $label
	 * @param \muuska\renderer\value\ValueRenderer $valueRenderer
	 * @return \muuska\html\HtmlGridFieldValue
	 */
	public function createHtmlGridFieldValue($name, $value, $htmlValue = null, \muuska\html\HtmlContent $label = null, \muuska\renderer\value\ValueRenderer $valueRenderer = null){
	    return new \muuska\html\HtmlGridFieldValue($name, $value, $htmlValue, $label, $valueRenderer);
	}
	
	/**
	 * @param string $name
	 * @param callable $callback
	 * @param array $initialParams
	 * @return \muuska\html\DefaultContentCreator
	 */
	public function createDefaultContentCreator($name, $callback, $initialParams = null){
	    return new \muuska\html\DefaultContentCreator($name, $callback, $initialParams);
	}
	
	/**
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param string $name
	 * @param \muuska\renderer\HtmlContentRenderer $renderer
	 * @return \muuska\html\Badge
	 */
	public function createBadge(\muuska\html\HtmlContent $innerContent = null, $name = null, \muuska\renderer\HtmlContentRenderer $renderer = null){
	    return new \muuska\html\Badge($innerContent, $name, $renderer);
	}
	
	/**
	 * @param string $name
	 * @param string $componentName
	 * @param \muuska\renderer\HtmlContentRenderer $renderer
	 * @return \muuska\html\HtmlComponent
	 */
	public function createHtmlComponent($name = null, $componentName = null, \muuska\renderer\HtmlContentRenderer $renderer = null){
	    return new \muuska\html\HtmlComponent($renderer, $componentName, $renderer);
	}	
	
	/**
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param string $name
	 * @param \muuska\renderer\HtmlContentRenderer $renderer
	 * @return \muuska\html\HtmlDiv
	 */
	public function createHtmlDiv(\muuska\html\HtmlContent $innerContent = null, $name = null, \muuska\renderer\HtmlContentRenderer $renderer = null){
	    return new \muuska\html\HtmlDiv($innerContent, $name, $renderer);
	}
	
	/**
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param string $name
	 * @param \muuska\renderer\HtmlContentRenderer $renderer
	 * @return \muuska\html\HtmlSpan
	 */
	public function createHtmlSpan(\muuska\html\HtmlContent $innerContent = null, $name = null, \muuska\renderer\HtmlContentRenderer $renderer = null){
	    return new \muuska\html\HtmlSpan($innerContent, $name, $renderer);
	}
	
	/**
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param string $name
	 * @param \muuska\renderer\HtmlContentRenderer $renderer
	 * @return \muuska\html\HtmlP
	 */
	public function createHtmlP(\muuska\html\HtmlContent $innerContent = null, $name = null, \muuska\renderer\HtmlContentRenderer $renderer = null){
	    return new \muuska\html\HtmlP($innerContent, $name, $renderer);
	}
	
	/**
	 * @param string $tag
	 * @param string $name
	 * @param string $componentName
	 * @param \muuska\renderer\HtmlContentRenderer $renderer
	 * @return \muuska\html\SelfClosingElement
	 */
	public function createSelfClosingElement($tag, $name = null, $componentName = null, \muuska\renderer\HtmlContentRenderer $renderer = null){
	    return new \muuska\html\SelfClosingElement($tag, $name, $componentName, $renderer);
	}
	
	/**
	 * @param string $tag
	 * @param \muuska\html\HtmlContent $innerContent
	 * @param string $name
	 * @param \muuska\renderer\HtmlContentRenderer $renderer
	 * @return \muuska\html\DefaultHtmlElement
	 */
	public function createDefaultHtmlElement($tag, \muuska\html\HtmlContent $innerContent = null, $name = null, \muuska\renderer\HtmlContentRenderer $renderer = null){
	    return new \muuska\html\DefaultHtmlElement($tag, $innerContent, $name, $renderer);
	}
	
	/**
	 * @param string $tag
	 * @param \muuska\html\HtmlContent[] $children
	 * @return \muuska\html\ChildWrapper
	 */
	public function createChildWrapper($tag, $children = array()){
	    return new \muuska\html\ChildWrapper($tag, $children);
	}
}
