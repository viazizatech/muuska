<?php
namespace muuska\helper;

use muuska\constants\DataType;
use muuska\constants\ExternalFieldViewType;
use muuska\constants\FieldNature;
use muuska\html\constants\ActionOpenMode;
use muuska\util\App;
use muuska\constants\operator\Operator;
use muuska\constants\ActionCode;

class ModelCrudViewHelper extends AbstractHelper
{
    /**
     * @var string
     */
    protected $name = 'view';
    
    protected $externalFieldsDefinition = array();
    
    protected $multipleAssociationsDefinition = array();
    
    /**
     * @var \muuska\model\AbstractModel
     */
    protected $loadedModel;
    
    /**
     * @var \muuska\dao\util\SelectionConfig
     */
    protected $selectionConfig;
    
    protected $urlParamForId = 'id';

    /**
     * @var bool
     */
    protected $innerNavigationEnabled;
    
    /**
     * @var array
     */
    protected $innerNavigationDefinition = array();

    /**
     * @var \muuska\dao\DAO
     */
    protected $dao;
    
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
     * @var \muuska\controller\param\ControllerParamResolver
     */
    protected $paramResolver;
    
    /**
     * @var \muuska\url\ControllerUrlCreator
     */
    protected $urlCreator;
    
    /**
     * @var string
     */
    protected $openMode;
    
    /**
     * @var string[]
     */
    protected $excludedFields = array('deleted');
    
    public function __construct(\muuska\controller\ControllerInput $input, \muuska\controller\param\ControllerParamResolver $paramResolver, \muuska\url\ControllerUrlCreator $urlCreator, \muuska\dao\DAO $dao, $externalFieldsDefinition = array(), \muuska\dao\util\SelectionConfig $selectionConfig = null)
    {
        $this->input = $input;
        $this->paramResolver = $paramResolver;
        $this->urlCreator = $urlCreator;
        $this->dao = $dao;
        
        $this->externalFieldsDefinition = $externalFieldsDefinition;
        $this->selectionConfig = $selectionConfig;
    }
    
    public function init() {
        $this->initSelectionConfig();
        $this->loadedModel = $this->dao->getUniqueModel($this->selectionConfig, false);
        if($this->loadedModel === null){
            $this->errors[] = $this->l('An error occurred while loading object');
        }
        $paramParsers = $this->paramResolver->getParsers();
        foreach ($paramParsers as $parser) {
            $parser->formatHelperView($this);
        }
    }
    
    public function initSelectionConfig() {
        $modelDefinition = $this->dao->getModelDefinition();
        
        $associations = array_keys($this->multipleAssociationsDefinition);
        if($this->selectionConfig === null){
            $this->selectionConfig = $this->input->createSelectionConfig();
            $identifiers = $modelDefinition->getPrimaryValuesFromString($this->input->getQueryParam($this->urlParamForId));
            $this->selectionConfig->createRestrictionFieldsFromArray($identifiers);
            $this->selectionConfig->setAllLangsEnabled(true);
            $externalFieldsDefinition = $this->externalFieldsDefinition;
            $fields = $this->dao->getModelDefinition()->getFieldDefinitions();
            foreach($fields as $field => $fieldDefinition){
                if(!in_array($field, $this->excludedFields)){
                    if(isset($fieldDefinition['nature']) && ($fieldDefinition['nature'] == FieldNature::EXISTING_MODEL_ID) && !isset($externalFieldsDefinition[$field])){
                        $externalFieldsDefinition[$field] = array('editionType' => ExternalFieldViewType::TO_STRING);
                    }
                }
            }
            foreach ($externalFieldsDefinition as $field => $externalFieldDefinition) {
                $this->formatExternalAssociation($field, $externalFieldDefinition);
            }
            foreach ($associations as $associationName) {
                $this->selectionConfig->createMultipleSelectionAssociation($associationName);
            }
        }
    }
    
    public function formatExternalAssociation($field, $externalFieldDefinition, \muuska\dao\util\SelectionAssociation $parentSelectionAssociation = null) {
        $currentParentSelectionAssociation = null;
        $useToString = (!isset($externalFieldDefinition['viewType']) || ($externalFieldDefinition['viewType'] == ExternalFieldViewType::TO_STRING));
        if($useToString || (isset($externalFieldDefinition['viewType']) && ($externalFieldDefinition['viewType'] == ExternalFieldViewType::ALL_FIELDS)) ||
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
     * @return \muuska\html\panel\Panel
     */
    public function createViewPanel(){
        $title = $this->title;
        
        if(empty($title)){
            $title = $this->l('View');
        }
        $viewPage = App::htmls()->createPanel($title);
        $viewPage->addClass('view_panel');
        $viewPage->setSubTitle($this->dao->getModelDefinition()->getModelPresentation($this->loadedModel));
        $backButton = App::htmls()->createHtmlLink(App::createHtmlString($this->l('Back')), $this->urlCreator->createDefaultUrl(), App::createFAIcon('arrow-left'), $this->l('Back'), true);
        $backButton->addClass('btn_view_back');
        $viewPage->addTool($backButton);
        if($this->renderer != null){
            $viewPage->setRenderer($this->renderer);
        }elseif (!empty($this->openMode)){
            if(($this->openMode == ActionOpenMode::IN_NAV) || ($this->openMode == ActionOpenMode::REPLACE)){
                $viewPage->setRenderer($this->getRendererFromName('panel/simple'));
            }elseif ($this->openMode == ActionOpenMode::MODAL){
                $viewPage->setRenderer($this->getRendererFromName('panel/modal'));
            }
        }
        if(!empty($this->openMode)){
            $viewPage->setUsedOpenMode($this->openMode);
        }
        $viewPage->setAjaxEnabled($this->ajaxEnabled);
        if(!empty($this->actionDefaultOpenMode)){
            $viewPage->setActionDefaultOpenMode($this->actionDefaultOpenMode);
        }
        return $viewPage;
    }
    
    /**
     * @param \muuska\html\ChildrenContainer $container
     */
    public function createFields(\muuska\html\ChildrenContainer $container){
        $modelDefinition = $this->dao->getModelDefinition();
        $primaries = $modelDefinition->getPrimaries();
        foreach($primaries as $field){
            if(!$modelDefinition->containsField($field) && !in_array($field, $this->excludedFields)){
                $this->addChildToContainerFromDefinition($this->dao, $container, $field, array('type' => DataType::TYPE_INT), $this->loadedModel, array());
            }
        }
        $fields = $modelDefinition->getFieldDefinitions();
        foreach($fields as $field => $fieldDefinition){
            if(!in_array($field, $this->excludedFields)){
                $externalFieldDefinition = isset($this->externalFieldsDefinition[$field]) ? $this->externalFieldsDefinition[$field] : null;
                $this->addChildToContainerFromDefinition($this->dao, $container, $field, $fieldDefinition, $this->loadedModel, $externalFieldDefinition);
            }
        }
    }
    
    public function addChildToContainerFromDefinition(\muuska\dao\DAO $dao, \muuska\html\ChildrenContainer $container, $field, $fieldDefinition, $model, $externalFieldDefinition = array())
    {
        if(!isset($fieldDefinition['nature']) || ($fieldDefinition['nature'] != FieldNature::PASSWORD)){
            $field = $this->createChildFromDefinition($dao, $fieldDefinition, $field, $model, $externalFieldDefinition);
            if($field != null){
                $container->addChild($field);
            }
        }
    }
    
    public function createChildFromDefinition(\muuska\dao\DAO $dao, $fieldDefinition, $field, $model, $externalFieldDefinition = null)
    {
        $child = null;
        $modelDefinition = $dao->getModelDefinition();
        $label = $this->translateModel($modelDefinition, $field, 'view');
        $nature = isset($fieldDefinition['nature']) ? $fieldDefinition['nature'] : '';
        if($nature==FieldNature::EXISTING_MODEL_ID){
            $label = isset($externalFieldDefinition['label']) ? $externalFieldDefinition['label'] : $label;
            if(!isset($externalFieldDefinition['viewType']) || ($externalFieldDefinition['viewType'] == ExternalFieldViewType::TO_STRING)){
                $externalModelDefinition = $modelDefinition->getAssociationDefinition($field);
                $associatedModel = $modelDefinition->getAssociatedModel($model, $field);
                $child = App::htmls()->createHtmlGridFieldValue($field, $associatedModel, null, App::createHtmlLabel($label), App::renderers()->createSimpleValueRenderer(App::getters()->createModelPresentationGetter($externalModelDefinition)));
            }elseif (isset($externalFieldDefinition['viewType']) && ($externalFieldDefinition['viewType'] == ExternalFieldViewType::ALL_FIELDS)){
                $child = App::htmls()->createChildrenContainer($label);
                $externalDao = $dao->getForeignDAO($field);
                $externalModelDefinition = $externalDao->getModelDefinition();
                $associatedModel = $modelDefinition->getAssociatedModel($model, $field);
                if($associatedModel !== null){
                    $externalFields = $externalModelDefinition->getFieldDefinitions();
                    foreach ($externalFields as $externalField => $externalFieldDefinition) {
                        if(!isset($externalFieldDefinition['excludedFields']) || !in_array($externalField, $externalFieldDefinition['excludedFields'])){
                            $subExternalFieldDefinition = null;
                            if(isset($externalFieldDefinition['externalFieldsDefinition']) && isset($externalFieldDefinition['externalFieldsDefinition'][$externalField])){
                                $subExternalFieldDefinition = $externalFieldDefinition['externalFieldsDefinition'][$externalField];
                            }
                            $this->addChildToContainerFromDefinition($externalDao, $child, $externalField, $externalFieldDefinition, $associatedModel, $subExternalFieldDefinition);
                        }
                    }
                }
            }else{
                $renderer = $this->createFieldValueRenderer($field, $fieldDefinition, $dao, App::getters()->createModelValueGetter($modelDefinition, $field));
                $child = App::htmls()->createHtmlGridFieldValue($field, $model, null, App::createHtmlLabel($label), $renderer);
            }
        }else{
            $renderer = $this->createFieldValueRenderer($field, $fieldDefinition, $dao, App::getters()->createModelValueGetter($modelDefinition, $field));
            $child = App::htmls()->createHtmlGridFieldValue($field, $model, null, App::createHtmlLabel($label), $renderer);
        }
        return $child;
    }
    
    /**
     * @return \muuska\html\panel\Panel
     */
    public function getPanel() {
        $viewPanel = $this->createViewPanel();
        $dataViewContainer = App::htmls()->createChildrenContainer();
        $this->createFields($dataViewContainer);
        $this->createMultipleAssociationComponents($dataViewContainer);
        
        if($this->innerNavigationEnabled){
            $nav = App::htmls()->createHtmlNav();
            $navItem = $nav->createItem('informations', App::createHtmlString($this->l('Informations')));
            $navItem->setNavContent($dataViewContainer);
            $navItem->setActive(true);
            $navItem->setLoaded(true);
            $fullNavigation = App::htmls()->createFullNavigation($nav);
            foreach ($this->innerNavigationDefinition as $navKey => $navDefinition) {
                if(isset($navDefinition['controllerName']) && isset($navDefinition['extraParamName']) && $this->input->getCurrentUser()->checkAccess($this->input->getProject()->createResourceTree($this->input->getSubAppName(), App::securities()->createResourceTree($navDefinition['controllerName'], App::securities()->createResourceTree(ActionCode::DEFAULT_PROCESS))))){
                    $urlParams = array($navDefinition['extraParamName'] => $this->dao->getModelDefinition()->getSinglePrimaryValue($this->loadedModel));
                    $url = $this->urlCreator->createControllerUrl($navDefinition['controllerName'], null, $urlParams);
                    $label = isset($navDefinition['label']) ? $navDefinition['label'] : '';
                    $title = isset($navDefinition['title']) ? $navDefinition['title'] : '';
                    $icon = isset($navDefinition['icon']) ? $navDefinition['icon'] : null;
                    $navItem = $nav->createItem($navKey, App::createHtmlString($label), $url, $icon, $title);
                    $navItem->setLoaded(false);
                }
            }
            $viewPanel->setInnerContent($fullNavigation);
        }else{
            $viewPanel->setInnerContent($dataViewContainer);
        }
        return $viewPanel;
    }
    
    public function createMultipleAssociationComponents(\muuska\html\ChildrenContainer $container){
        $modelDefinition = $this->dao->getModelDefinition();
        foreach ($this->multipleAssociationsDefinition as $associationName => $multipleAssociationDefinition) {
            $multipleAssociationModelDefinition = $modelDefinition->getMultipleAssociationDefinition($associationName);
            if(isset($multipleAssociationDefinition['field'])){
                $externalDao = $this->dao->getMultipleAssociationDAO($associationName)->getForeignDAO($multipleAssociationDefinition['field']);
                $externalModelDefinition = $externalDao->getModelDefinition();
                $selectedModels = $modelDefinition->getMultipleAssociatedModels($this->loadedModel, $associationName);
                $modelGetter = App::getters()->createModelValueGetter($multipleAssociationModelDefinition, $multipleAssociationDefinition['field']);
                $selectedIds = App::getArrayTools()->getArrayValues($selectedModels, $modelGetter);
                $label = isset($multipleAssociationDefinition['label']) ? $multipleAssociationDefinition['label'] : $this->translateModel($modelDefinition, $associationName, 'form');
                $list = null;
                $parentField = $externalModelDefinition->getParentField();
                $data = array();
                $selectionConfig = $this->input->createSelectionConfig();
                $selectionConfig->addRestrictionFieldFromParams($externalModelDefinition->getPrimary(), $selectedIds, Operator::IN_LIST);
                if($externalModelDefinition->containsField($parentField)){
                    $selectionConfig->addRestrictionFieldFromParams($parentField, null);
                    $data = $externalDao->getData($selectionConfig);
                    $list = App::htmls()->createHtmlTree($data);
                    $subSelectionConfig = $this->input->createSelectionConfig();
                    $subSelectionConfig->addRestrictionFieldFromParams($externalModelDefinition->getPrimary(), $selectedIds, Operator::IN_LIST);
                    $list->setSubValuesGetter(App::getters()->createChildrenModelGetter($externalDao, $subSelectionConfig));
                }else{
                    $data = $externalDao->getData($selectionConfig);
                    $list = App::htmls()->createTable($data);
                    $list->addClassesFromString('table-striped table-bordered');
                    $list->setHeaderDisabled(true);
                }
                if(count($data) > 0){
                    $list->createTitleField(App::renderers()->createSimpleValueRenderer(App::getters()->createModelPresentationGetter($externalDao->getModelDefinition())));
                    $container->addChild(App::htmls()->createGridFormField($associationName, App::createHtmlLabel($label), $list), $associationName);
                }
            }
        }
    }
    
    public function addControllerInnerNavigation($name, $label, $paramName, $title = ''){
        $this->innerNavigationDefinition[$name] = array('label' => $label, 'controllerName' => $name, 'title' => $title, 'extraParamName' => $paramName);
    }
    
    public function setRendererFromName($name){
        $this->renderer = $this->getRendererFromName($name);
    }
    
    /**
     * @return array
     */
    public function getExternalFieldsDefinition()
    {
        return $this->externalFieldsDefinition;
    }
    
    /**
     * @return array
     */
    public function getMultipleAssociationsDefinition()
    {
        return $this->multipleAssociationsDefinition;
    }
    
    /**
     * @param array $externalFieldsDefinition
     */
    public function setExternalFieldsDefinition($externalFieldsDefinition)
    {
        $this->externalFieldsDefinition = $externalFieldsDefinition;
    }
    
    public function addExternalFieldDefinition($field, $viewType, $excludedFields = null, $subExternalFieldsDefinition = null)
    {
        if(empty($excludedFields)){
            $excludedFields = array('creationDate', 'lastModifiedDate', 'deleted');
        }
        $definition = array('viewType' => $viewType, 'excludedFields' => $excludedFields, 'excludedFields' => $excludedFields);
        if(!empty($subExternalFieldsDefinition)){
            $definition['externalFieldsDefinition'] = $subExternalFieldsDefinition;
        }
        
        $this->addExternalFieldDefinitionFromArray($field, $definition);
    }
    
    public function addMultipleAssociation($associationName, $field, $label = null)
    {
        $this->multipleAssociationsDefinition[$associationName] = array('field' => $field);
        if(!empty($label)){
            $this->multipleAssociationsDefinition[$associationName]['label'] = $label;
        }
    }
    
    public function addExternalFieldDefinitionFromArray($field, $definition)
    {
        $this->externalFieldsDefinition[$field] = $definition;
    }
    
    /**
     * @param array $multipleAssociationsDefinition
     */
    public function setMultipleAssociationsDefinition($multipleAssociationsDefinition)
    {
        $this->multipleAssociationsDefinition = $multipleAssociationsDefinition;
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
     * @return boolean
     */
    public function isAjaxEnabled()
    {
        return $this->ajaxEnabled;
    }
    
    /**
     * @return string
     */
    public function getActionDefaultOpenMode()
    {
        return $this->actionDefaultOpenMode;
    }
    
    /**
     * @return string
     */
    public function getOpenMode()
    {
        return $this->openMode;
    }
    
    /**
     * @param boolean $ajaxEnabled
     */
    public function setAjaxEnabled($ajaxEnabled)
    {
        $this->ajaxEnabled = $ajaxEnabled;
    }
    
    /**
     * @param string $actionDefaultOpenMode
     */
    public function setActionDefaultOpenMode($actionDefaultOpenMode)
    {
        $this->actionDefaultOpenMode = $actionDefaultOpenMode;
    }
    
    /**
     * @param string $openMode
     */
    public function setOpenMode($openMode)
    {
        $this->openMode = $openMode;
    }
    
    /**
     * @return \muuska\controller\param\ControllerParamResolver
     */
    public function getParamResolver()
    {
        return $this->paramResolver;
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
     * @return object
     */
    public function getLoadedModel()
    {
        return $this->loadedModel;
    }
    
    /**
     * @return string
     */
    public function getUrlParamForId()
    {
        return $this->urlParamForId;
    }
    
    /**
     * @return \muuska\dao\DAO
     */
    public function getDao()
    {
        return $this->dao;
    }
    
    /**
     * @return \muuska\url\ControllerUrlCreator
     */
    public function getUrlCreator()
    {
        return $this->urlCreator;
    }
    
    /**
     * @param string $urlParamForId
     */
    public function setUrlParamForId($urlParamForId)
    {
        $this->urlParamForId = $urlParamForId;
    }
    
    /**
     * @return \muuska\dao\util\SelectionConfig
     */
    public function getSelectionConfig()
    {
        return $this->selectionConfig;
    }

    /**
     * @return boolean
     */
    public function isInnerNavigationEnabled()
    {
        return $this->innerNavigationEnabled;
    }

    /**
     * @return array
     */
    public function getInnerNavigationDefinition()
    {
        return $this->innerNavigationDefinition;
    }

    /**
     * @param array $innerNavigationDefinition
     */
    public function setInnerNavigationDefinition($innerNavigationDefinition)
    {
        $this->innerNavigationDefinition = $innerNavigationDefinition;
    }
    
    /**
     * @param boolean $innerNavigationEnabled
     */
    public function setInnerNavigationEnabled($innerNavigationEnabled)
    {
        $this->innerNavigationEnabled = $innerNavigationEnabled;
    }
}