<?php
namespace muuska\helper;

use muuska\constants\DataType;
use muuska\constants\FieldNature;
use muuska\dao\constants\SortDirection;
use muuska\html\constants\ActionOpenMode;
use muuska\html\constants\ListType;
use muuska\util\App;
use muuska\html\constants\IconPosition;
use muuska\constants\operator\Operator;
use muuska\html\constants\ButtonStyle;

class ModelListHelper extends AbstractHelper
{
    /**
     * @var string
     */
    protected $name = 'list';
    
    /**
     * @var array
     */
    protected $filterPrefixes = array('specific' => 'specific_filter_', 'inner' => 'inner_filter_', 'quick' => 'quick_filter_');
	
	/**
	 * @var string[]
	 */
	protected $excludedFields = array('creationDate', 'lastModifiedDate', 'deleted');
	
	/**
	 * @var int
	 */
	protected $defaultSortDirection = SortDirection::DESC;
	
	/**
	 * @var string
	 */
	protected $defaultSortField;
	
	/**
	 * @var \muuska\dao\util\SelectionConfig
	 */
	protected $selectionConfig;
	
	/**
	 * @var int
	 */
	protected $listType = ListType::TABLE;
	
	/**
	 * @var \muuska\dao\DAO
	 */
	protected $dao;
	
	/**
	 * @var string
	 */
	protected $recorderKey;
	
    /**
     * @var int
     */
    protected $defaultItemsPerPage = 20;
    
    /**
     * @var array
     */
    protected $externalFieldsDefinition = array();
    
    /**
     * @var string
     */
    protected $title;
    
    /**
     * @var \muuska\renderer\HtmlContentRenderer
     */
    protected $renderer;
    
    /**
     * @var bool
     */
    protected $ajaxEnabled;
    
    /**
     * @var string
     */
    protected $actionDefaultOpenMode;
    
    /**
     * @var string
     */
    protected $openMode;
    
    /**
     * @var \muuska\controller\ControllerInput
     */
    protected $input;
    
    /**
     * @var \muuska\controller\param\ControllerParamResolver
     */
    protected $paramResolver;
    
    /**
     * @var \muuska\url\ControllerUrlCreator
     */
    protected $urlCreator;
    
    /**
     * @var bool
     */
    protected $quickSearchEnabled;
    
    /**
     * @var bool
     */
    protected $specificSearchEnabled;
    
    /**
     * @var bool
     */
    protected $innerSearchEnabled = true;
    
    /**
     * @var bool
     */
    protected $specificSortEnabled;
    
    /**
     * @var bool
     */
    protected $filterDataStorageEnabled = true;
    
    /**
     * @var bool
     */
    protected $bulkActionEnabled = true;
    
    /**
     * @var bool
     */
    protected $limiterEnabled = true;
    
    /**
     * @var bool
     */
    protected $currentControllerInfoEnabled;
	
	public function __construct(\muuska\controller\ControllerInput $input, \muuska\controller\param\ControllerParamResolver $paramResolver, \muuska\url\ControllerUrlCreator $urlCreator, \muuska\dao\DAO $dao, $recorderKey, $externalFieldsDefinition = array(), \muuska\dao\util\SelectionConfig $selectionConfig = null){
	    $this->input = $input;
	    $this->paramResolver = $paramResolver;
	    $this->urlCreator = $urlCreator;
	    $this->recorderKey = $recorderKey;
	    $this->dao = $dao;
	    
	    $this->selectionConfig = $selectionConfig;
	    if($this->selectionConfig === null){
	        $this->selectionConfig = $this->input->createSelectionConfig();
	    }
	    $this->externalFieldsDefinition = $externalFieldsDefinition;
	}
	
	public function init()
    {
        $paramParsers = $this->paramResolver->getParsers();
        foreach ($paramParsers as $parser) {
            $parser->formatHelperList($this);
        }
    }
	
    public function createListPanel()
    {
        $title = $this->title;
        
        if(empty($title)){
            $definition = $this->dao->getModelDefinition();
            $title = $this->translateModel($definition, $definition->getName(), 'list_title');
        }
        $listPanel = App::htmls()->createListPanel($title);
        if($this->renderer !== null){
            $listPanel->setRenderer($this->renderer);
        }elseif (!empty($this->openMode)){
            if(($this->openMode == ActionOpenMode::IN_NAV) || ($this->openMode == ActionOpenMode::REPLACE)){
                $listPanel->setRenderer($this->getRendererFromName('panel/list/simple'));
            }elseif ($this->openMode == ActionOpenMode::MODAL){
                $listPanel->setRenderer($this->getRendererFromName('panel/list/modal'));
            }
        }
        $listPanel->addClass('list_panel');
        if(!empty($this->openMode)){
            $listPanel->setUsedOpenMode($this->openMode);
        }
        $listPanel->setAjaxEnabled($this->ajaxEnabled);
        if(!empty($this->actionDefaultOpenMode)){
            $listPanel->setActionDefaultOpenMode($this->actionDefaultOpenMode);
        }
        return $listPanel;
    }
    
    public function createList($innerSearchData = null) {
        $list = null;
        $identifierGetter = App::getters()->createModelIdentifierGetter($this->dao->getModelDefinition());
        if($this->listType == ListType::DEFAULT_LIST){
            $list = App::htmls()->createDefaultList();
        }elseif($this->listType == ListType::TABLE){
            $list = App::htmls()->createTable();
            if($this->innerSearchEnabled){
                $list->addAttribute('data-search_url', $this->urlCreator->createDefaultUrl(array('innerFilter' => 1)));
                $list->setSearchResetEnabled(!empty($innerSearchData));
                $searchButton = App::htmls()->createButton(App::createHtmlString($this->l('Search')), 'button', App::createFAIcon('search'), ButtonStyle::PRIMARY);
                $searchButton->addClass('search_btn');
                $list->setSearchAction($searchButton);
                $searchResetButton = App::htmls()->createHtmlLink(App::createHtmlString($this->l('Reset')), $this->urlCreator->createDefaultUrl(array('resetInnerFilter' => 1)), App::createFAIcon('times'), $this->l('Reset'), true);
                $searchResetButton->addClass('search_reset_btn');
                $list->setSearchResetAction($searchResetButton);
            }
        }elseif($this->listType == ListType::TREE){
            $list = App::htmls()->createHtmlTree();
            $list->setRenderer($this->getRendererFromName('listing/tree/accordion/list'));
            $list->setItemRenderer($this->getRendererFromName('listing/tree/accordion/item'));
            $list->setSubValuesGetter(App::getters()->createChildrenModelGetter($this->dao, $this->input->createSelectionConfig()));
            if($this->paramResolver->hasParam('parent')){
                $paramParent = $this->paramResolver->getParam('parent');
                $newSelectionConfig = $this->input->createSelectionConfig();
                $newSelectionConfig->setLangEnabled(false);
                $parents = $this->dao->getParents($paramParent->getObject(), $newSelectionConfig)->getArrayValues($identifierGetter);
                $parents[] = $paramParent->getValue();
                $itemCreator = App::htmls()->createDefaultListItemCreator(function($initialParams, $data, \muuska\html\listing\item\ListItemContainer $listItemContainer, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
                    $item = $listItemContainer->defaultCreateItem($data, $globalConfig, $callerConfig);
                    if(in_array($item->getDataId($globalConfig, $callerConfig), $initialParams['parents'])){
                        $item->addClass('open');
                    }
                    return $item;
                }, array('parents' => $parents));
                $list->setItemCreator($itemCreator);
            }
        }
        if($list !== null){
            $list->setActionText($this->l('Actions'));
            $list->setEmptyText($this->l('No data found'));
            $list->setIdentifier('ids');
            $list->setIdentifierGetter($identifierGetter);
        }
        return $list;
    }
	
	public function createBulkActions(\muuska\html\panel\ListPanel $listPanel) {
		
	}
	
	public function createConfirmText($action) {
		return sprintf($this->l('Are you sure you want to %s this item?'), $this->l($action)).'<br/>'.$this->l('Detail : %s');
	}
	
	public function createBulkConfirmText($action) {
		return sprintf($this->l('Are you sure you want to %s these items?'), $this->l($action));
	}
	
	public function createFields(\muuska\html\listing\AbstractList $list, \muuska\html\ChildrenContainer $specificSearchContainer = null, \muuska\html\AbstractChildWrapper $specificSortContainer = null, $searchData = array(), $activeSortField = null, $activeSortDirection = null)
	{
	    
	}
	public function createFieldFromDefinition(\muuska\html\listing\AbstractList $list, $field, $fieldDefinition, $label, \muuska\dao\DAO $dao, $fieldKey, \muuska\html\ChildrenContainer $specificSearchContainer = null, \muuska\html\AbstractChildWrapper $specificSortContainer = null, $searchData = array(), $activeSortField = null, $activeSortDirection = null, $extraParams = null, $externalFieldDefinition = null, $externalParams = null)
    {
        $nature = isset($fieldDefinition['nature']) ? $fieldDefinition['nature'] : '';
        $associatedModelGetter = isset($externalParams['associatedModelGetter']) ? $externalParams['associatedModelGetter'] : null;
        $parentSelectionAssociation = isset($externalParams['parentSelectionAssociation']) ? $externalParams['parentSelectionAssociation'] : null;
        if($nature == FieldNature::EXISTING_MODEL_ID){
            $hidden = isset($externalFieldDefinition['hidden']) ? $externalFieldDefinition['hidden'] : false;
            $listField = null;
            $externalDao = $dao->getForeignDAO($field);
            $externalModelDefinition = $externalDao->getModelDefinition();
            $useToString = (!isset($externalFieldDefinition['useToString']) || $externalFieldDefinition['useToString']);
            
            $currentParentSelectionAssociation = null;
            if($useToString || (isset($externalFieldDefinition['otherFields']) && !empty($externalFieldDefinition['otherFields'])) ||
                (isset($externalFieldDefinition['externalFieldsDefinition']) && !empty($externalFieldDefinition['externalFieldsDefinition'])))
            {
                if($parentSelectionAssociation === null){
                    $currentParentSelectionAssociation = $this->selectionConfig->setSelectionAssociationParams($field);
                }else{
                    $currentParentSelectionAssociation = $parentSelectionAssociation->addSubAssociationFromParams($field);
                }
            }
            
            if(!$hidden && $useToString){
                $newAssociatedModelGetter = ($associatedModelGetter === null) ? App::getters()->createAssociatedModelGetter($dao->getModelDefinition(), $field) : $associatedModelGetter->createNew($field);
                $listField = $list->createField($fieldKey, App::renderers()->createSimpleValueRenderer(App::getters()->createModelPresentationGetter($newAssociatedModelGetter->getModelDefinition()->getAssociationDefinition($field), $newAssociatedModelGetter)), $label);
            }elseif(!$hidden){
                $listField = $list->createField($fieldKey, App::renderers()->createSimpleValueRenderer(App::getters()->createModelValueGetter($dao->getModelDefinition(), $field, $associatedModelGetter)), $label);
            }
            if($listField !== null){
                $this->autoCreateSearchField($list, $field, $fieldDefinition, $label, $dao, $fieldKey, $listField, $specificSearchContainer, $searchData, $extraParams, $externalFieldDefinition, $externalParams);
                $this->createSortLinks($field, $fieldDefinition, $label, $dao, $fieldKey, $specificSortContainer, $activeSortField, $activeSortDirection, $extraParams, $externalFieldDefinition, $externalParams, $listField);
            }
            if (isset($externalFieldDefinition['otherFields']) && !empty($externalFieldDefinition['otherFields'])){
                $newAssociatedModelGetter = ($associatedModelGetter === null) ? App::getters()->createAssociatedModelGetter($dao->getModelDefinition(), $field) : $associatedModelGetter->createNew($field);
                foreach ($externalFieldDefinition['otherFields'] as $otherField => $otherFieldParam) {
                    if($externalModelDefinition->containsField($otherField)){
                        $subExternalFieldDefinition = (isset($externalFieldDefinition['externalFieldsDefinition']) && isset($externalFieldDefinition['externalFieldsDefinition'][$otherField])) ? $externalFieldDefinition['externalFieldsDefinition'][$otherField] : null;
                        $otherFieldLabel = isset($otherFieldParam['label']) ? $otherFieldParam['label'] : $otherField;
                        $otherFieldKey = isset($otherFieldParam['fieldKey']) ? $otherFieldParam['fieldKey'] : $fieldKey.'_'.$otherField;
                        $newParentFields = isset($externalParams['parentFields']) ? $externalParams['parentFields'] : array();
                        $newParentFields[] = $field;
                        $newExternalParams = array('associatedModelGetter' => $newAssociatedModelGetter, 'parentSelectionAssociation' => $currentParentSelectionAssociation, 'parentFields' => $newParentFields);
                        $newExtraParams = isset($otherFieldParam['extraParams']) ? $otherFieldParam['extraParams'] : null;
                        $this->createFieldFromDefinition($list, $otherField, $externalModelDefinition->getFieldDefinition($otherField), $otherFieldLabel, $externalDao, $otherFieldKey, $specificSearchContainer, $specificSortContainer, $searchData, $activeSortField, $activeSortDirection, $newExtraParams, $subExternalFieldDefinition, $newExternalParams);
                    }
                }
            }
        }elseif($nature != FieldNature::PASSWORD){
            $valueGetter = App::getters()->createModelValueGetter($dao->getModelDefinition(), $field, $associatedModelGetter);
            $valueRenderer = $this->createFieldValueRenderer($field, $fieldDefinition, $dao, $valueGetter, $associatedModelGetter);
            $listField = $list->createField($fieldKey, $valueRenderer, $label);
            
            if(($nature != FieldNature::IMAGE) && ($nature != FieldNature::FILE)){
                $this->autoCreateSearchField($list, $field, $fieldDefinition, $label, $dao, $fieldKey, $listField, $specificSearchContainer, $searchData, $extraParams, $externalFieldDefinition, $externalParams);
                $this->createSortLinks($field, $fieldDefinition, $label, $dao, $fieldKey, $specificSortContainer, $activeSortField, $activeSortDirection, $extraParams, $externalFieldDefinition, $externalParams, $listField);
            }
        }
	}
	
	protected function autoCreateSearchField(\muuska\html\listing\AbstractList $list, $field, $fieldDefinition, $label, \muuska\dao\DAO $dao, $fieldKey, \muuska\html\listing\ListField $listField, \muuska\html\ChildrenContainer $specificSearchContainer = null, $searchData = array(), $extraParams = null, $externalFieldDefinition = null, $externalParams = null)
	{
	    if(!isset($extraParams['searchable']) || $extraParams['searchable']){
	        if($specificSearchContainer !== null){
	            $specificSearchValue = (isset($searchData['specific']) && isset($searchData['specific'][$fieldKey])) ? $searchData['specific'][$fieldKey] : null;
	            $searchComponent = $this->createSearchField($field, $fieldDefinition, $dao, $this->filterPrefixes['specific'].$fieldKey, $specificSearchValue, $externalFieldDefinition, $externalParams);
	            if($searchComponent !== null){
	                $searchField = App::htmls()->createFormField($fieldKey, App::createHtmlLabel($label), $searchComponent);
	                $specificSearchContainer->addChild($searchField);
	            }
	        }
	        if($this->innerSearchEnabled && ($this->listType == ListType::TABLE)){
	            
	            $innerSearchValue = (isset($searchData['inner']) && isset($searchData['inner'][$fieldKey])) ? $searchData['inner'][$fieldKey] : null;
	            $searchComponent = $this->createSearchField($field, $fieldDefinition, $dao, $this->filterPrefixes['inner'].$fieldKey, $innerSearchValue, $externalFieldDefinition, $externalParams);
	            if($searchComponent !== null){
	                $listField->setSearch($searchComponent);
	            }
	        }
	    }
	}
	protected function createSearchField($field, $fieldDefinition, \muuska\dao\DAO $dao, $searchFieldName, $searchValue = null, $externalFieldDefinition = null, $externalParams = null)
	{
	    $result = null;
	    $nature = isset($fieldDefinition['nature']) ? $fieldDefinition['nature'] : '';
	    $type = isset($fieldDefinition['type']) ? $fieldDefinition['type'] : '';
	    $operator = null;
	    $restrictionEnabled = ($searchValue !== null);
	    $formatAsToString = false;
	    if($nature == FieldNature::EXISTING_MODEL_ID){
	        if(isset($externalFieldDefinition['searchAsSelect']) && $externalFieldDefinition['searchAsSelect']){
	            $data = $dao->getForeignDAO($field)->getData($this->input->createSelectionConfig());
	            $result = App::htmls()->createSelect($searchFieldName, $data->toOptionProvider(), $searchValue, true);
	        }elseif(!isset($externalFieldDefinition['useToString']) || $externalFieldDefinition['useToString']){
	            $result = App::htmls()->createHtmlInput('text', $searchFieldName, $searchValue);
	            $formatAsToString = true;
	        }else{
	            $result = App::htmls()->createHtmlInput('text', $searchFieldName, $searchValue);
	        }
	    }elseif(($type == DataType::TYPE_DATE) || ($type == DataType::TYPE_DATETIME)){
	        $array = $this->getArrayFromValue($searchValue);
	        $min = isset($array[0]) ? $array[0] : null;
	        $max = isset($array[1]) ? $array[1] : null;
	        
	        $result = App::htmls()->createCustomInputGroup(array(App::htmls()->createHtmlInput('text', $searchFieldName.'[0]', $min, $this->l('From')), App::htmls()->createHtmlInput('text', $searchFieldName.'[1]', $max, $this->l('To'))));
	        $language = $this->input->getLanguageInfo();
	        $result->convertToRangeDatePicker((($language !== null) ? $language->getLanguage() : null));
	        if(empty($min) && !empty($max)){
	            $min = date('Y-m-d');
	        }elseif (!empty($min) && empty($max)){
	            $max = date('Y-m-d');
	        }
	        if(empty($min) || empty($max)){
	            $restrictionEnabled = false;
	        }else{
	            $operator = Operator::BETWEEN;
	            $searchValue = array($min, $max);
	        }
	    }elseif(($nature == FieldNature::OBJECT_STATE) || ($nature == FieldNature::OPTION)){
	        if(isset($fieldDefinition['optionProvider'])){
	            $result = App::htmls()->createSelect($searchFieldName, $fieldDefinition['optionProvider']->getLangOptionProvider($this->input->getLang()), $searchValue, true);
	        }
	    }elseif($type==DataType::TYPE_BOOL){
	        $result = App::htmls()->createSelect($searchFieldName, App::options()->createBoolProvider($this->input->getLang()), $searchValue, true);
	    }elseif(($type == DataType::TYPE_INT) || ($type == DataType::TYPE_FLOAT) || ($type == DataType::TYPE_DECIMAL)){
	        $array = $this->getArrayFromValue($searchValue);
	        $min = isset($array[0]) ? $array[0] : null;
	        $max = isset($array[1]) ? $array[1] : null;
	        
	        $result = App::htmls()->createCustomInputGroup(array(App::htmls()->createHtmlInput('text', $searchFieldName.'[0]', $min, $this->l('From')), App::htmls()->createHtmlInput('text', $searchFieldName.'[1]', $max, $this->l('To'))));
	        $result->addClass('numeric_search');
	        if(empty($min) && !empty($max)){
	            $min = 0;
	        }elseif (!empty($min) && empty($max)){
	            $max = $min;
	        }
	        if(empty($min) || empty($max)){
	            $restrictionEnabled = false;
	        }else{
	            $operator = Operator::BETWEEN;
	            $searchValue = array($min, $max);
	        }
	    }else{
	        $result = App::htmls()->createHtmlInput('text', $searchFieldName, $searchValue);
	        if(($type == DataType::TYPE_STRING) || ($type == DataType::TYPE_HTML)){
	            $operator = Operator::CONTAINS;
	        }
	    }
	    if($restrictionEnabled){
	        $fieldRestriction = App::daos()->createFieldRestriction($field, $searchValue, $operator);
	        $this->formatFieldParameter($fieldRestriction, $dao, $field, $externalParams, $formatAsToString);
	        $this->selectionConfig->addRestrictionField($fieldRestriction, $searchFieldName);
	    }
	    return $result;
	}
	protected function createSortLinks($field, $fieldDefinition, $label, \muuska\dao\DAO $dao, $fieldKey, \muuska\html\AbstractChildWrapper $specificSortContainer = null, $activeSortField = null, $activeSortDirection = null, $extraParams = null, $externalFieldDefinition = null, $externalParams = null, \muuska\html\listing\ListField $listField = null)
	{
	    if(!isset($extraParams['sortable']) || $extraParams['sortable']){
	        if(($specificSortContainer !== null)){
	            $ascSortLink = $this->createSortLink($field, $fieldDefinition, $fieldKey, SortDirection::ASC, $dao, $activeSortField, $activeSortDirection, $externalFieldDefinition, $externalParams, App::createFAIcon('long-arrow-alt-up'), $this->l('Asc'));
	            $ascSortLink->addClass('asc');
	            $descSortLink = $this->createSortLink($field, $fieldDefinition, $fieldKey, SortDirection::DESC, $dao, $activeSortField, $activeSortDirection, $externalFieldDefinition, $externalParams, App::createFAIcon('long-arrow-alt-down'), $this->l('Desc'));
	            $descSortLink->addClass('desc');
	            $linkWrapper = App::htmls()->createChildWrapper('span', array($ascSortLink, $descSortLink));
	            $linkWrapper->addClass('sort_links');
	            $sortItem = App::htmls()->createChildWrapper('div', array(App::createHtmlString($label), $linkWrapper));
	            $sortItem->addClass('sort_item');
	            $sortItem->setName($fieldKey);
	            $specificSortContainer->addChild($sortItem);
	        }elseif(($listField !== null) && ($this->listType == ListType::TABLE)){
	            $ascSortLink = $this->createSortLink($field, $fieldDefinition, $fieldKey, SortDirection::ASC, $dao, $activeSortField, $activeSortDirection, $externalFieldDefinition, $externalParams, App::createFAIcon('long-arrow-alt-up'), $this->l('Asc'));
	            $ascSortLink->addClass('asc');
	            $descSortLink = $this->createSortLink($field, $fieldDefinition, $fieldKey, SortDirection::DESC, $dao, $activeSortField, $activeSortDirection, $externalFieldDefinition, $externalParams, App::createFAIcon('long-arrow-alt-down'), $this->l('Desc'));
	            $descSortLink->addClass('desc');
	            $listField->setAscSort($ascSortLink);
	            $listField->setDescSort($descSortLink);
	        }
	    }
	}
	protected function createSortLink($field, $fieldDefinition, $fieldKey, $direction, \muuska\dao\DAO $dao, $activeSortField, $activeSortDirection, $externalFieldDefinition, $externalParams, \muuska\html\HtmlContent $innerContent = null, $title = '', \muuska\html\HtmlContent $icon = null)
	{
	    $link = App::htmls()->createHtmlLink($innerContent, $this->urlCreator->createDefaultUrl(array('sortList'=>1, 'field' => $fieldKey, 'direction' => $direction)), $icon, $title);
	    if(($activeSortField === $fieldKey) && ($activeSortDirection == $direction)){
	        $link->addClass('active');
	        $sortOption = App::daos()->createSortOption($field, $direction);
	        $useToString = false;
	        $nature = isset($fieldDefinition['nature']) ? $fieldDefinition['nature'] : '';
	        if($nature == FieldNature::EXISTING_MODEL_ID){
	            $useToString = (!isset($externalFieldDefinition['useToString']) || $externalFieldDefinition['useToString']);
	        }
	        
	        $this->formatFieldParameter($sortOption, $dao, $field, $externalParams, $useToString);
	        $this->selectionConfig->addSortOption($sortOption, $fieldKey);
	    }
	    return $link;
	}
	protected function getArrayFromValue($value)
	{
	    $result = array();
	    if(is_array($value)){
	        $result = $value;
	    }elseif(!empty($value)){
	        $array = unserialize($value);
	        $result = is_array($array) ? $array : array();
	    }
	    return $result;
	}
	protected function formatFieldParameter(\muuska\dao\util\FieldParameter $fieldParameter, \muuska\dao\DAO $dao, $field, $externalParams, $formatAsToString = false)
	{
	    if(isset($externalParams['parentFields'])){
	        $fieldParameter->setForeign(true);
	        $fieldParameter->setExternalField($field);
	        $currentFieldParameter = $fieldParameter;
	        $first = true;
	        foreach ($externalParams['parentFields'] as $parentField) {
	            if($first){
	                $first = false;
	                $fieldParameter->setFieldName($parentField);
	            }else{
	                $currentFieldParameter->setExternalField($parentField);
	                $currentFieldParameter = $currentFieldParameter->setSubExternalFieldFromParams($field);
	            }
	        }
	        if($formatAsToString){
	            $dao->getForeignDAO($field)->getModelDefinition()->formatPresentationFieldParameter($fieldParameter, true, $fieldParameter->getFieldName());
	        }
	    }
	}
	
	/**
	 * @param string $field
	 * @param array $externalFieldDefinition
	 * @param \muuska\dao\util\SelectionAssociation $parentSelectionAssociation
	 */
	public function formatExternalAssociation($field, $externalFieldDefinition, \muuska\dao\util\SelectionAssociation $parentSelectionAssociation = null) {
	    $currentParentSelectionAssociation = null;
	    if((isset($externalFieldDefinition['useToString']) && $externalFieldDefinition['useToString']) || 
	        (isset($externalFieldDefinition['otherFields']) && !empty($externalFieldDefinition['otherFields'])) ||
	        (isset($externalFieldDefinition['externalFieldsDefinition']) && !empty($externalFieldDefinition['externalFieldsDefinition'])))
	    {
	        if($parentSelectionAssociation === null){
	            $currentParentSelectionAssociation = $this->selectionConfig->setSelectionAssociationParams($field);
	        }else{
	            $currentParentSelectionAssociation = $parentSelectionAssociation->addSubAssociationFromParams($field);
	        }
	    }
	    if(isset($externalFieldDefinition['externalFieldsDefinition']) && !empty($externalFieldDefinition['externalFieldsDefinition'])){
	        foreach ($externalFieldDefinition['externalFieldsDefinition'] as $externalField => $otherExternalFieldDefinition) {
	            $this->formatExternalAssociation($externalField, $otherExternalFieldDefinition, $currentParentSelectionAssociation);
	        }
	    }
	}
    
    /**
     * @param string $filterPrefix
     * @return array
     */
    public function retrieveSearchDataFromRequest($filterPrefix){
        $result = array();
        $allValues = $this->input->getRequest()->getPostParams();
        foreach($allValues as $key => $value){
            if(strpos($key, $filterPrefix)===0){
                $emptyValue = false;
                if(is_array($value)){
                    $emptyValue = true;
                    foreach($value as $val){
                        $emptyValue = ($emptyValue && ($val===''));
                    }
                }else{
                    $emptyValue = ($value==='');
                }
                if(!$emptyValue){
                    $result[App::getStringTools()->strReplaceOnce($filterPrefix, '', $key)] = $value;
                }
            }
        }
        return $result;
    }
    
    public function storeInnerSearchDataFromRequest(){
        $searchData = $this->retrieveSearchDataFromRequest($this->filterPrefixes['inner']);
        $this->storeSearchData($this->recorderKey.$this->filterPrefixes['inner'], $searchData);
    }
    
    public function storeSpecificSearchDataFromRequest(){
        $searchData = $this->retrieveSearchDataFromRequest($this->filterPrefixes['specific']);
        $this->storeSearchData($this->recorderKey.$this->filterPrefixes['specific'], $searchData);
    }
    
    public function storeQuickSearchDataFromRequest(){
        $searchData = $this->retrieveSearchDataFromRequest($this->filterPrefixes['quick']);
        $this->storeSearchData($this->recorderKey.$this->filterPrefixes['quick'], $searchData);
    }
    
    /**
     * @param string $prefix
     * @param array $searchData
     */
    public function storeSearchData($prefix, $searchData){
        $recorder = $this->input->getVisitorInfoRecorder();
        $this->resetFilterByPrefix($prefix);
        if (is_array($searchData)) {
            foreach($searchData as $key => $value){
                $field = $prefix.$key;
                $recorder->setValue($field,  (is_array($value) ? serialize($value) : $value));
            }
        }
	}
	
	public function storeSortInfo($field, $direction){
	    $recorder = $this->input->getVisitorInfoRecorder();
	    $prefix = $this->recorderKey.'sort_options_';
	    $recorder->setValue($prefix.'field', $field);
	    $recorder->setValue($prefix.'direction', (int)$direction);
	}
	
	public function storeItemsPerPage($itemsPerPage){
	    $this->input->getVisitorInfoRecorder()->setValue($this->recorderKey.'list_limit', $itemsPerPage);
	}
	
	/**
	 * @param string $prefix
	 */
	public function resetFilterByPrefix($prefix){
	    $this->input->getVisitorInfoRecorder()->removeValuesByPrefix($prefix);
	}
	
	public function resetInnerFilters(){
	    $this->resetFilterByPrefix($this->recorderKey.$this->filterPrefixes['inner']);
	}
	
	public function resetSpecificFilters(){
	    $this->resetFilterByPrefix($this->recorderKey.$this->filterPrefixes['specific']);
	}
	
	public function resetQuickFilters(){
	    $this->resetFilterByPrefix($this->recorderKey.$this->filterPrefixes['quick']);
	}
	
	/**
	 * @param string $prefix
	 * @return array
	 */
	public function retrieveStoredSearchData($prefix){
	    $result = array();
	    $data = $this->input->getVisitorInfoRecorder()->getValuesByPrefix($prefix);
	    if (is_array($data)) {
	        foreach ($data as $key => $value) {
	            $result[App::getStringTools()->removePrefix($key, $prefix)] = $value;
	        }
	    }
	    return $result;
	}
	
	public function getSortInfo(){
	    $prefix = $this->recorderKey.'sort_options_';
	    $result = array('field' => null, 'direction' => null);
	    $storedField = $this->input->getVisitorInfoRecorder()->getValue($prefix.'field');
	    if(!empty($storedField)){
	        $result['field'] = $storedField;
	        $result['direction'] = (int)$this->input->getVisitorInfoRecorder()->getValue($prefix.'direction', SortDirection::ASC);
	    }else{
	        if(empty($this->defaultSortField)){
	            $modelDefinition = $this->dao->getModelDefinition();
	            if($modelDefinition->containsField($modelDefinition->getPositionField())){
	                $this->defaultSortField = $modelDefinition->getPositionField();
	                $this->defaultSortDirection = SortDirection::ASC;
	            }elseif ($modelDefinition->isAutoIncrement()){
	                $this->defaultSortField = $modelDefinition->getPrimary();
	                $this->defaultSortDirection = SortDirection::DESC;
	            }
	        }
	        $result['field'] = $this->defaultSortField;
	        $result['direction'] = (int)$this->defaultSortDirection;
	    }
	    if(empty($result['direction'])){
	        $result['direction'] = SortDirection::ASC;
	    }
	    return $result;
	}
	
	/**
	 * @param int $itemsPerPage
	 * @param int $currentPage
	 * @return \muuska\dao\util\DAOListResult
	 */
	public function getListData($itemsPerPage = 20, $currentPage = 1){
	    $this->selectionConfig->setLimit($itemsPerPage);
	    $this->selectionConfig->setStartValueFromPage($currentPage);
	    $this->selectionConfig->setDataCountingEnabled(true);
	    if($this->listType == ListType::TREE){
	        $this->selectionConfig->addRestrictionFieldFromParams($this->dao->getModelDefinition()->getParentField(), null);
	    }
	    $daoListResult = $this->dao->getData($this->selectionConfig);
	    return $daoListResult;
	}
	
	public function createSpecificSearchContainer(\muuska\html\panel\ListPanel $listPanel = null, $specificSearchData = null){
	    $form = App::htmls()->createForm($this->urlCreator->createDefaultUrl(array('specificFilter' => 1)));
	    $form->addClass('specific_filter_form');
	    $form->setLabel($this->l('Advanced Search Form'));
	    $form->setRenderer($this->getRendererFromName('form/multi_column_form'));
	    $form->setSubmit(App::htmls()->createButton(App::createHtmlString($this->l('Search')), 'submit', App::createFAIcon('search'), ButtonStyle::PRIMARY));
	    if(!empty($specificSearchData)){
	        $searchResetButton = App::htmls()->createHtmlLink(App::createHtmlString($this->l('Reset')), $this->urlCreator->createDefaultUrl(array('resetSpecificFilter' => 1)), App::createFAIcon('times'), $this->l('Reset'), true, ButtonStyle::SECONDARY);
	        $searchResetButton->addClass('search_reset_btn');
	        $form->setCancel($searchResetButton);
	    }
	    if($listPanel !== null){
	        $formId = 'filter_form_'.date('Y-m-d-H-i-s');
	        $form->setId($formId);
	        $form->addClass('collapse');
	        $listPanel->setSpecificSearchArea($form);
	        $buttonFilter = App::htmls()->createButton(App::createHtmlString($this->l('Filter')), 'button', App::createFAIcon('filter'));
	        $buttonFilter->addAttribute('data-toggle', 'collapse');
	        $buttonFilter->addAttribute('data-target', '#'.$formId);
	        $listPanel->addTool($buttonFilter);
	    }
	    return $form;
	}
	
	public function createSpecificSortContainer(\muuska\html\panel\ListPanel $listPanel = null, $sortInfo = null){
	    $dropDown = App::htmls()->createDropdown(App::createHtmlString($this->l('Sort by')));
	    if(isset($sortInfo['field']) && !empty($sortInfo['field'])){
	        $dropDown->setActiveChild($sortInfo['field']);
	    }
	    $dropDown->addClass('specific_sort');
	    if($listPanel !== null){
	        $listPanel->setSortArea($dropDown);
	    }
	    return $dropDown;
	}
	
	public function createQuickSearchForm(\muuska\html\panel\ListPanel $listPanel = null, $value = null){
	    $form = App::htmls()->createQuickSearchForm($this->urlCreator->createDefaultUrl(array('quickFilter' => 1)), App::htmls()->createHtmlInput('text',$this->filterPrefixes['quick'].'search_value', $value, $this->l('Search')), true, IconPosition::RIGHT);
	    $form->setCustomIcon(App::createFAIcon('search'));
	    if(($value !== null) || (trim($value) !== '')){
	        $restrictionField = $this->dao->createFieldRestriction(null, $value, Operator::CONTAINS);
	        $this->dao->getModelDefinition()->formatPresentationFieldParameter($restrictionField);
	        $this->selectionConfig->addRestrictionField($restrictionField, 'quick_search_field');
	    }
	    if($listPanel !== null){
	        $listPanel->setQuickSearchArea($form);
	    }
	    return $form;
	}
	
	public function getSearchData() {
	    $result = array('inner' => null, 'quick' => null, 'specific' => null);
	    if($this->filterDataStorageEnabled){
	        $result['inner'] = $this->retrieveStoredSearchData($this->recorderKey.$this->filterPrefixes['inner']);
	        $result['specific'] = $this->retrieveStoredSearchData($this->recorderKey.$this->filterPrefixes['specific']);
	        $quickValues = $this->retrieveStoredSearchData($this->recorderKey.$this->filterPrefixes['quick']);
	        $result['quick'] = isset($quickValues['search_value']) ? $quickValues['search_value'] : null;
	    }else{
	        $result['inner'] = $this->retrieveSearchDataFromRequest($this->filterPrefixes['inner']);
	        $result['specific'] = $this->retrieveSearchDataFromRequest($this->filterPrefixes['specific']);;
	        $quickValues = $this->retrieveSearchDataFromRequest($this->filterPrefixes['quick']);
	        $result['quick'] = isset($quickValues['search_value']) ? $quickValues['search_value'] : null;
	    }
	    return $result;
	}
	
	/**
	 * @return \muuska\html\panel\ListPanel
	 */
	public function prepareList(){
	    $searchData = $this->getSearchData();
	    $sortInfo = $this->getSortInfo();
	    $list = $this->createList((isset($searchData['inner']) ? $searchData['inner'] : null));
	    $activeSortField = isset($sortInfo['field']) ? $sortInfo['field'] : null;
	    $activeSortDirection = isset($sortInfo['direction']) ? $sortInfo['direction'] : null;
	    $listPanel = $this->createListPanel();
	    $specificSearchContainer = null;
	    if($this->specificSearchEnabled){
	        $specificSearchContainer = $this->createSpecificSearchContainer($listPanel, (isset($searchData['specific']) ? $searchData['specific'] : null));
	    }
	    $specificSortContainer = null;
	    if($this->specificSortEnabled){
	        $specificSortContainer = $this->specificSortEnabled ? $this->createSpecificSortContainer($listPanel, $sortInfo) : null;
	    }
	    
	    $this->createFields($list, $specificSearchContainer, $specificSortContainer, $searchData, $activeSortField, $activeSortDirection);
	    if($this->quickSearchEnabled){
	        $this->createQuickSearchForm($listPanel, (isset($searchData['quick']) ? $searchData['quick'] : null));
	    }
	    $listPanel->setInnerContent($list);
	    $itemsPerPage = $this->getItemsPerPage();
	    $currentPage = $this->getCurrentPage();
	    $daoListResult = $this->getListData($itemsPerPage, $currentPage);
	    if($this->bulkActionEnabled && (count($daoListResult) > 0)){
	        $selectedDataIndicator = App::htmls()->createHtmlSpan();
	        $selectedDataIndicator->addAttribute('data-plural_text', $this->l('(%d selected)', 'plural'));
	        $selectedDataIndicator->addAttribute('data-singular_text', $this->l('(%d selected)', 'singular'));
	        $selectedDataIndicator->addClass('selected_data_indicator');
	        $selectedDataIndicator->setVisible(false);
	        $listPanel->setSelectedDataIndicator($selectedDataIndicator);
	        $this->createBulkActions($listPanel);
	    }
	    if($listPanel->hasBulkActionArea()){
	        $list->setItemSelectorEnabled(true);
	    }
	    $total = $daoListResult->getTotalWithoutLimit();
	    if($total > 0){
	        $listPanel->setTotalResultString(sprintf($this->l('%d Total'), $total));
	    }
	    $this->createPagination($daoListResult, $itemsPerPage, $currentPage, $listPanel);
	    
	    $this->createLimiterSwitcher($daoListResult, $itemsPerPage, $listPanel);
	    $list->setData($daoListResult);
	    return $listPanel;
	}
	
	/**
	 * @return int
	 */
	public function getItemsPerPage()
	{
	    $value = $this->input->getVisitorInfoRecorder()->getValue($this->recorderKey.'list_limit');
	    if($value === null){
	        $value = $this->defaultItemsPerPage;
	    }
	    return (int)$value;
	}
	
	/**
	 * @return int
	 */
	public function getCurrentPage()
	{
	    $page = (int)$this->input->getQueryParam('p');
	    return ($page === 0) ? 1 : $page;
	}
	
	/**
	 * \muuska\dao\util\DAOListResult $daoListResult
	 * @param int $itemsPerPage
	 * @param int $currentPage
	 * @param \muuska\html\panel\ListPanel $listPanel
	 * @return \muuska\html\listing\pagination\Pagination
	 */
	public function createPagination(\muuska\dao\util\DAOListResult $daoListResult, $itemsPerPage = 20, $currentPage = 1, $listPanel = null)
	{
	    $pagination = null;
	    $totalResult = $daoListResult->getTotalWithoutLimit();
	    
	    if(($itemsPerPage > 0) && ($totalResult > $itemsPerPage)){
	        $pagination = App::htmls()->createPagination($totalResult, $itemsPerPage, $currentPage, 5, $this->urlCreator);
	        if($listPanel !== null){
	            $listPanel->setPagination($pagination);
	            $startIndex = (((int)$currentPage - 1) * $itemsPerPage) + 1;
	            $strDesc = sprintf($this->l('Showing %1$d - %2$d of %3$d'), $startIndex, ($currentPage * $itemsPerPage), $totalResult);
	            $listPanel->setPaginationDescription(App::createHtmlString($strDesc));
	        }
	    }
	    return $pagination;
	}
	
	/**
	 * @param \muuska\dao\util\DAOListResult $daoListResult
	 * @param int $itemsPerPage
	 * @param \muuska\html\panel\ListPanel $listPanel
	 * @return \muuska\html\listing\ListLimiterSwitcher
	 */
	public function createLimiterSwitcher(\muuska\dao\util\DAOListResult $daoListResult, $itemsPerPage = 20, $listPanel = null)
	{
	    $limiterSwitcher = null;
	    $totalResult = $daoListResult->getTotalWithoutLimit();
	    $minItemsPerPage = 20;
	    if($this->limiterEnabled && ($totalResult > $minItemsPerPage)){
	        $array = array($minItemsPerPage => $minItemsPerPage, '50' => '50', '100' => '100', '200' => '200', '0' => $this->l('All'));
	        $optionProvider = App::options()->createKeyValueOptionProvider($array);
	        $limiterSwitcher = App::htmls()->createListLimiterSwitcher($optionProvider, $itemsPerPage, $this->urlCreator);
	        if($listPanel !== null){
	            $listPanel->setLimiterSwitcher($limiterSwitcher);
	        }
	    }
	    return $limiterSwitcher;
	}
	
	/**
	 * @param string $name
	 */
	public function setRendererFromName($name){
	    $this->renderer = $this->getRendererFromName($name);
	}
	
	/**
	 * @param string $field
	 */
	public function addExcludedField($field) {
	    $this->excludedFields[] = $field;
	}
	
	/**
	 * @param string[] $fields
	 */
	public function addExcludedFields($fields) {
	    if(is_array($fields)){
	        foreach ($fields as $field) {
	            $this->addExcludedField($field);
	        }
	    }
	}
	
	/**
	 * @return string[]
	 */
	public function getExcludedFields()
	{
	    return $this->excludedFields;
	}
	
	/**
	 * @param string[] $excludedFields
	 */
	public function setExcludedFields($excludedFields)
	{
	    $this->excludedFields = array();
	    $this->addExcludedFields($excludedFields);
	}
	
    /**
     * @return int
     */
    public function getListType()
    {
        return $this->listType;
    }

    /**
     * @return array
     */
    public function getExternalFieldsDefinition()
    {
        return $this->externalFieldsDefinition;
    }

    /**
     * @param int $listType
     */
    public function setListType($listType)
    {
        $this->listType = $listType;
    }

    /**
     * @param array $externalFieldsDefinition
     */
    public function setExternalFieldsDefinition($externalFieldsDefinition)
    {
        $this->externalFieldsDefinition = $externalFieldsDefinition;
    }
    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    /**
     * @return \muuska\renderer\HtmlContentRenderer
     */
    public function getRenderer()
    {
        return $this->renderer;
    }

    /**
     * @param \muuska\renderer\HtmlContentRenderer $renderer
     */
    public function setRenderer(?\muuska\renderer\HtmlContentRenderer $renderer)
    {
        $this->renderer = $renderer;
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
     * @return string
     */
    public function getActionDefaultOpenMode()
    {
        return $this->actionDefaultOpenMode;
    }

    /**
     * @param string $actionDefaultOpenMode
     */
    public function setActionDefaultOpenMode($actionDefaultOpenMode)
    {
        $this->actionDefaultOpenMode = $actionDefaultOpenMode;
    }
    /**
     * @return string
     */
    public function getOpenMode()
    {
        return $this->openMode;
    }

    /**
     * @param string $openMode
     */
    public function setOpenMode($openMode)
    {
        $this->openMode = $openMode;
    }

    /**
     * @return int
     */
    public function getDefaultSortDirection()
    {
        return $this->defaultSortDirection;
    }

    /**
     * @return string
     */
    public function getDefaultSortField()
    {
        return $this->defaultSortField;
    }

    /**
     * @return \muuska\dao\util\SelectionConfig
     */
    public function getSelectionConfig()
    {
        return $this->selectionConfig;
    }

    /**
     * @param int $defaultSortDirection
     */
    public function setDefaultSortDirection($defaultSortDirection)
    {
        $this->defaultSortDirection = $defaultSortDirection;
    }

    /**
     * @param string $defaultSortField
     */
    public function setDefaultSortField($defaultSortField)
    {
        $this->defaultSortField = $defaultSortField;
    }
    
    /**
     * @return \muuska\controller\param\ControllerParamResolver
     */
    public function getParamResolver()
    {
        return $this->paramResolver;
    }
    /**
     * @return boolean
     */
    public function isQuickSearchEnabled()
    {
        return $this->quickSearchEnabled;
    }

    /**
     * @return boolean
     */
    public function isSpecificSearchEnabled()
    {
        return $this->specificSearchEnabled;
    }

    /**
     * @return boolean
     */
    public function isInnerSearchEnabled()
    {
        return $this->innerSearchEnabled;
    }

    /**
     * @param boolean $quickSearchEnabled
     */
    public function setQuickSearchEnabled($quickSearchEnabled)
    {
        $this->quickSearchEnabled = $quickSearchEnabled;
    }

    /**
     * @param boolean $specificSearchEnabled
     */
    public function setSpecificSearchEnabled($specificSearchEnabled)
    {
        $this->specificSearchEnabled = $specificSearchEnabled;
    }

    /**
     * @param boolean $innerSearchEnabled
     */
    public function setInnerSearchEnabled($innerSearchEnabled)
    {
        $this->innerSearchEnabled = $innerSearchEnabled;
    }
    
    /**
     * @param int $defaultItemsPerPage
     */
    public function setDefaultItemsPerPage($defaultItemsPerPage)
    {
        $this->defaultItemsPerPage = $defaultItemsPerPage;
    }
    
    /**
     * @return int
     */
    public function getDefaultItemsPerPage()
    {
        return $this->defaultItemsPerPage;
    }
    
    /**
     * @return boolean
     */
    public function isFilterDataStorageEnabled()
    {
        return $this->filterDataStorageEnabled;
    }

    /**
     * @param boolean $filterDataStorageEnabled
     */
    public function setFilterDataStorageEnabled($filterDataStorageEnabled)
    {
        $this->filterDataStorageEnabled = $filterDataStorageEnabled;
    }
    /**
     * @return boolean
     */
    public function isSpecificSortEnabled()
    {
        return $this->specificSortEnabled;
    }

    /**
     * @param boolean $specificSortEnabled
     */
    public function setSpecificSortEnabled($specificSortEnabled)
    {
        $this->specificSortEnabled = $specificSortEnabled;
    }
    /**
     * @return boolean
     */
    public function isBulkActionEnabled()
    {
        return $this->bulkActionEnabled;
    }

    /**
     * @param boolean $bulkActionEnabled
     */
    public function setBulkActionEnabled($bulkActionEnabled)
    {
        $this->bulkActionEnabled = $bulkActionEnabled;
    }
    
    public function addExternalFieldDefinition($field, $definition) {
        $this->externalFieldsDefinition[$field] = $definition;
    }
    
    /**
     * @return boolean
     */
    public function isLimiterEnabled()
    {
        return $this->limiterEnabled;
    }

    /**
     * @param boolean $limiterEnabled
     */
    public function setLimiterEnabled($limiterEnabled)
    {
        $this->limiterEnabled = $limiterEnabled;
    }
    
    /**
     * @return boolean
     */
    public function isCurrentControllerInfoEnabled()
    {
        return $this->currentControllerInfoEnabled;
    }

    /**
     * @param boolean $currentControllerInfoEnabled
     */
    public function setCurrentControllerInfoEnabled($currentControllerInfoEnabled)
    {
        $this->currentControllerInfoEnabled = $currentControllerInfoEnabled;
    }
}