<?php
namespace muuska\helper;

use muuska\constants\ActionCode;
use muuska\constants\DataType;
use muuska\constants\ExternalFieldEditionType;
use muuska\constants\FieldNature;
use muuska\html\constants\ActionOpenMode;
use muuska\html\constants\AlertType;
use muuska\http\constants\RedirectionType;
use muuska\util\App;
use muuska\html\constants\ButtonStyle;

class ModelFormHelper extends AbstractHelper
{
    /**
     * @var string
     */
    protected $name = 'form';
    
    /**
     * @var bool
     */
    protected $submitted;
    
    protected $externalFieldsDefinition = array();
    
    protected $multipleAssociationsDefinition = array();
    
    /**
     * @var bool
     */
    protected $update;
    
    /**
     * @var array
     */
    protected $defaultValues;
    
    /**
     * @var \muuska\dao\util\SaveConfig
     */
    protected $saveConfig;
    
    /**
     * @var object
     */
    protected $defaultModel;
    
    /**
     * @var object
     */
    protected $loadedModel;
    
    /**
     * @var \muuska\dao\util\SelectionConfig
     */
    protected $loadedModelSelectionConfig;
    
    
    protected $urlParamForId = 'id';
    
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
     * @var string
     */
    protected $openMode;
    
    /**
     * @var \muuska\controller\param\ControllerParamResolver
     */
    protected $paramResolver;
    
    /**
     * @var \muuska\url\ControllerUrlCreator
     */
    protected $urlCreator;
    
    /**
     * @var string[]
     */
    protected $excludedFields = array('creationDate', 'lastModifiedDate', 'deleted');
    
    /**
     * @var bool
     */
    protected $crudSubmitButtonEnabled;
    
    public function __construct(\muuska\controller\ControllerInput $input, \muuska\controller\param\ControllerParamResolver $paramResolver, \muuska\url\ControllerUrlCreator $urlCreator, \muuska\dao\DAO $dao, $update, $externalFieldsDefinition = array(), \muuska\dao\util\SelectionConfig $loadedModelSelectionConfig = null)
    {
        $this->input = $input;
        $this->paramResolver = $paramResolver;
        $this->urlCreator = $urlCreator;
        $this->dao = $dao;
        
        $this->externalFieldsDefinition = $externalFieldsDefinition;
        $this->update = $update;
        $this->loadedModelSelectionConfig = $loadedModelSelectionConfig;
        $this->saveConfig = $this->input->createSaveConfig();
    }
    
    public function init() {
        $paramParsers = $this->paramResolver->getParsers();
        foreach ($paramParsers as $parser) {
            $parser->formatHelperForm($this);
        }
        $this->submitted = $this->input->hasPostParam('submitted');
        $this->defaultModel = $this->dao->createModel();
        $modelDefinition = $this->dao->getModelDefinition();
        
        if($this->isUpdate()){
            $this->initLoadedModelSelectionConfig();
            $this->loadedModel = $this->dao->getUniqueModel($this->loadedModelSelectionConfig, false);
            if($this->loadedModel !== null){
                $modelDefinition = 
                $this->defaultModel = $modelDefinition->duplicateModel($this->loadedModel);
            }else{
                $this->errors[] = $this->l('An error occurred while loading object');
            }
        }
    }
    
    public function initLoadedModelSelectionConfig() {
        if($this->loadedModelSelectionConfig === null){
            $this->loadedModelSelectionConfig = $this->input->createSelectionConfig();
            $identifiers = $this->dao->getModelDefinition()->getPrimaryValuesFromString($this->input->getQueryParam($this->urlParamForId));
            $this->loadedModelSelectionConfig->createRestrictionFieldsFromArray($identifiers);
            $this->loadedModelSelectionConfig->setAllLangsEnabled(true);
            foreach ($this->externalFieldsDefinition as $field => $externalFieldDefinition) {
                $this->formatExternalAssociation($field, $externalFieldDefinition);
            }
            $associations = array_keys($this->multipleAssociationsDefinition);
            foreach ($associations as $associationName) {
                $this->loadedModelSelectionConfig->createMultipleSelectionAssociation($associationName);
            }
        }
    }
    
    public function formatExternalAssociation($field, $externalFieldDefinition, \muuska\dao\util\SelectionAssociation $parentSelectionAssociation = null) {
        $currentParentSelectionAssociation = null;
        if((isset($externalFieldDefinition['editionType']) && ($externalFieldDefinition['editionType'] == ExternalFieldEditionType::ALL_FIELDS)) ||
            (isset($externalFieldDefinition['externalFieldsDefinition']) && !empty($externalFieldDefinition['externalFieldsDefinition'])))
        {
            if($parentSelectionAssociation === null){
                $currentParentSelectionAssociation = $this->loadedModelSelectionConfig->setSelectionAssociationParams($field, null, true, true, true);
            }else{
                $currentParentSelectionAssociation = $parentSelectionAssociation->addSubAssociationFromParams($field, null, true, true, true);
            }
        }
        if(isset($externalFieldDefinition['externalFieldsDefinition']) && !empty($externalFieldDefinition['externalFieldsDefinition'])){
            foreach ($externalFieldDefinition['externalFieldsDefinition'] as $externalField => $otherExternalFieldDefinition) {
                $this->formatExternalAssociation($externalField, $otherExternalFieldDefinition, $currentParentSelectionAssociation);
            }
        }
    }
    public function retrieveSubmittedData()
    {
        $this->retrieveModelData($this->isUpdate(), $this->saveConfig, $this->dao, $this->defaultModel, '', $this->excludedFields, $this->defaultValues, $this->externalFieldsDefinition, $this->multipleAssociationsDefinition);
    }

    public function isSubmitted()
    {
        return $this->submitted;
    }

    public function saveData()
    {
        $result = false;
        if($this->isUpdate()){
            $result = $this->dao->update($this->defaultModel, $this->saveConfig);
        }else{
            $result = $this->dao->add($this->defaultModel, $this->saveConfig);
        }
        return $result;
    }

    public function afterSave($saveResult)
    {
        $this->finalizeUploadDefault($saveResult, $this->dao->getModelDefinition(), $this->dao, $this->defaultModel, $this->loadedModel, $this->excludedFields, $this->externalFieldsDefinition);
    }
    
    public function isUpdate(){
        return $this->update;
    }
    
    public function createResult($saveProceed, $saved, $content){
        $redirection = null;
        if($saveProceed && $saved){
            $submitType = $this->input->getPostParam('submitType');
            if($submitType === 'btn_save_and_stay'){
                $redirection = App::https()->createDynamicRedirection(RedirectionType::INNER_ACTION, null, ActionCode::UPDATE, array($this->urlParamForId => $this->dao->getModelDefinition()->getSinglePrimaryValue($this->defaultModel)), $this->input->getAction());
            }elseif($submitType === 'btn_save_and_view'){
                $redirection = App::https()->createDynamicRedirection(RedirectionType::INNER_ACTION, null, ActionCode::VIEW, array($this->urlParamForId => $this->dao->getModelDefinition()->getSinglePrimaryValue($this->defaultModel)), $this->input->getAction());
            }elseif($submitType === 'btn_save_and_add_new'){
                $redirection = App::https()->createDynamicRedirection(RedirectionType::INNER_ACTION, null, ActionCode::ADD, array(), $this->input->getAction());
            }else{
                $redirection = App::https()->createDynamicRedirection(RedirectionType::DEFAULT_ACTION, null, null, array(), $this->input->getAction());
            }
        }
        $allAlerts = empty($this->errors) ? array() : array(AlertType::DANGER);
        $result = App::utils()->createDefaultNavigationResult($saveProceed, $saved, $redirection, $content, $allAlerts);
        return $result;
    }
    
    /**
     * @return \muuska\html\panel\Panel
     */
    public function createFormPanel(){
        $title = $this->title;
        
        if(empty($title)){
            $title = $this->isUpdate() ? $this->l('Edit') : $this->l('Add new');
        }
        $formPanel = App::htmls()->createPanel($title);
        if($this->isUpdate()){
            $formPanel->setSubTitle($this->dao->getModelDefinition()->getModelPresentation($this->loadedModel));
        }
        $formPanel->addClass('form_panel');
        if($this->renderer != null){
            $formPanel->setRenderer($this->renderer);
        }elseif (!empty($this->openMode)){
            if(($this->openMode == ActionOpenMode::IN_NAV) || ($this->openMode == ActionOpenMode::REPLACE)){
                $formPanel->setRenderer($this->getRendererFromName('panel/simple'));
            }elseif ($this->openMode == ActionOpenMode::MODAL){
                $formPanel->setRenderer($this->getRendererFromName('panel/modal'));
            }
        }
        if(!empty($this->openMode)){
            $formPanel->setUsedOpenMode($this->openMode);
        }
        $formPanel->setAjaxEnabled($this->ajaxEnabled);
        if(!empty($this->actionDefaultOpenMode)){
            $formPanel->setActionDefaultOpenMode($this->actionDefaultOpenMode);
        }
        return $formPanel;
    }
    public function setRendererFromName($name){
        $this->renderer = $this->getRendererFromName($name);
    }
    
    public function createForm(\muuska\html\panel\Panel $formPanel = null, \muuska\validation\result\ModelValidationResult $validationResult = null){
        $actionUrl = '';
        if($this->isUpdate()){
            $actionUrl = $this->urlCreator->createUrl(ActionCode::UPDATE, array($this->urlParamForId => $this->input->getQueryParam($this->urlParamForId)));
        }else{
            $actionUrl = $this->urlCreator->createUrl(ActionCode::ADD);
        }
        $form = App::htmls()->createForm($actionUrl);
        $this->createSubmitButton($form, $formPanel);
        $btnCancel = App::htmls()->createHtmlLink(App::createHtmlString($this->l('Cancel')), $this->urlCreator->createDefaultUrl(), null, $this->l('Cancel'), true);
        $btnCancel->addClass('btn_cancel');
        $form->setCancel($btnCancel);
        if(($validationResult !== null) && !$validationResult->isValid()){
            $form->setErrorText($this->l('Some field are invalid'));
        }
        if($formPanel !== null){
            $formPanel->setInnerContent($form);
        }
        return $form;
    }
    
    public function createSubmitButton(\muuska\html\form\Form $form, \muuska\html\panel\Panel $formPanel = null) {
        $submitButton = App::htmls()->createButton(App::createHtmlString($this->l('Save')), 'submit', null, ButtonStyle::PRIMARY);
        if($this->crudSubmitButtonEnabled && ($formPanel !== null)){
            $buttonId = 'btn_save_'.date('Y-m-d-H-i-s');
            $submitButton->setId($buttonId);
            $dropdown = App::htmls()->createSplitDropdown($submitButton);
            $dropdown->setButtonStyle(ButtonStyle::PRIMARY);
            $dropdown->addMenuClass('dropdown-menu-right');
            
            $mirrorLink = App::htmls()->createHtmlLink($submitButton->getInnerContent(), '#', null, $this->l('Save'), true, ButtonStyle::PRIMARY);
            $mirrorLink->addAttribute('data-target', '#'.$buttonId);
            $mirrorLink->addClass('mirror_link');
            $mirrorDropdown = App::htmls()->createSplitDropdown($mirrorLink);
            $mirrorDropdown->setButtonStyle(ButtonStyle::PRIMARY);
            $mirrorDropdown->addMenuClass('dropdown-menu-right');
            $this->createDropdowButtonItem($dropdown, $mirrorDropdown, 'btn_save_and_add_new', $this->l('Save and add new'), ActionCode::ADD);
            $this->createDropdowButtonItem($dropdown, $mirrorDropdown, 'btn_save_and_view', $this->l('Save and view'), ActionCode::VIEW);
            $this->createDropdowButtonItem($dropdown, $mirrorDropdown, 'btn_save_and_stay', $this->l('Save and stay'));
            $this->createDropdowButtonItem($dropdown, $mirrorDropdown, 'btn_save_and_exit', $this->l('Save and exit'));
            if($formPanel !== null){
                $formPanel->addTool($mirrorDropdown);
            }
            $form->addChild(App::htmls()->createInputHidden('submitType'));
            $form->setSubmit($dropdown);
        }else{
            $form->setSubmit($submitButton);
        }
    }
    
    public function createDropdowButtonItem(\muuska\html\dropdown\Dropdown $dropdown, \muuska\html\dropdown\Dropdown $mirrorDropdown, $name, $label, $action = null) {
        if(empty($action) || $this->isActionEnabled($action)){
            $linkId = $name.date('Y-m-d-H-i-s');
            $link = App::htmls()->createHtmlLink(App::createHtmlString($label), '#', null, $label);
            $link->setId($linkId);
            $link->addClass('other_submit_btn');
            $link->addAttribute('data-name', $name);
            $dropdown->addChild($link);
            
            $mirrorLink = App::htmls()->createHtmlLink(App::createHtmlString($label), '#', null, $label);
            $mirrorLink->addAttribute('data-target', '#'.$linkId);
            $mirrorLink->addClass('mirror_link');
            $mirrorDropdown->addChild($mirrorLink);
        }
    }
    
    public function renderForm(\muuska\validation\result\ModelValidationResult $validationResult = null)
    {
        $formPanel = $this->createFormPanel();
        $backButton = App::htmls()->createHtmlLink(App::createHtmlString($this->l('Back')), $this->urlCreator->createDefaultUrl(), App::createFAIcon('arrow-left'), $this->l('Back'), true);
        $backButton->addClass('btn_form_back');
        $formPanel->addTool($backButton);
        $form = $this->createForm($formPanel, $validationResult);
        
        $this->createFields($form, $validationResult);
        $this->createMultipleAssociationComponents($form, $validationResult);
        return $formPanel;
    }
    
    public function createFields(\muuska\html\form\Form $form, \muuska\validation\result\ModelValidationResult $validationResult = null){
        $fieldDefinitions = $this->dao->getModelDefinition()->getFieldDefinitions();
        foreach($fieldDefinitions as $field => $fieldDefinition){
            if(!in_array($field, $this->excludedFields)){
                $externalFieldDefinition = isset($this->externalFieldsDefinition[$field]) ? $this->externalFieldsDefinition[$field] : null;
                $defaultValue = isset($this->defaultValues[$field]) ? $this->defaultValues[$field] : null;
                $this->addChildToContainerFromDefinition($form, $field, $fieldDefinition, $this->dao, $this->defaultModel, $this->loadedModel, $field, $externalFieldDefinition, $validationResult, $defaultValue);
            }
        }
    }
    
    public function createMultipleAssociationComponents(\muuska\html\form\Form $form, \muuska\validation\result\ModelValidationResult $validationResult = null){
        $modelDefinition = $this->dao->getModelDefinition();
        foreach ($this->multipleAssociationsDefinition as $associationName => $multipleAssociationDefinition) {
            $multipleAssociationModelDefinition = $modelDefinition->getMultipleAssociationDefinition($associationName);
            if(isset($multipleAssociationDefinition['field'])){
                $externalDao = $this->dao->getMultipleAssociationDAO($associationName)->getForeignDAO($multipleAssociationDefinition['field']);
                $externalModelDefinition = $externalDao->getModelDefinition();
                $selectedModels = array();
                $selectedModels = $modelDefinition->getMultipleAssociatedModels($this->defaultModel, $associationName);
                $modelGetter = App::getters()->createModelValueGetter($multipleAssociationModelDefinition, $multipleAssociationDefinition['field']);
                $selectedIds = App::getArrayTools()->getArrayValues($selectedModels, $modelGetter);
                $label = isset($multipleAssociationDefinition['label']) ? $multipleAssociationDefinition['label'] : $this->translateModel($modelDefinition, $associationName, 'form');
                $list = null;
                $parentField = $externalModelDefinition->getParentField();
                $selectionConfig = $this->input->createSelectionConfig();
                if(isset($multipleAssociationDefinition['extraRestrictions'])){
                    $selectionConfig->createRestrictionFieldsFromArray($multipleAssociationDefinition['extraRestrictions']);
                }
                if($externalModelDefinition->containsField($parentField)){
                    $selectionConfig->addRestrictionFieldFromParams($parentField, null);
                    $data = $externalDao->getData($selectionConfig);
                    $list = App::htmls()->createHtmlTree($data);
                    $list->setSubValuesGetter(App::getters()->createChildrenModelGetter($externalDao, $this->input->createSelectionConfig()));
                }else{
                    $data = $externalDao->getData($selectionConfig);
                    $list = App::htmls()->createTable($data);
                    $list->addClass('selectable_table');
                    $list->addClassesFromString('table-striped table-bordered table-hover table-checkable');
                }
                if(count($data) > 0){
                    $list->createTitleField(App::renderers()->createSimpleValueRenderer(App::getters()->createModelPresentationGetter($externalDao->getModelDefinition())), $this->l('All'));
                    $list->setSelectedIds($selectedIds);
                    $list->setItemSelectorEnabled(true);
                    $list->setIdentifier($associationName);
                    $list->setIdentifierGetter(App::getters()->createModelIdentifierGetter($externalDao->getModelDefinition()));
                    $formChild = App::htmls()->createGridFormField($associationName, App::createHtmlLabel($label), $list);
                    $form->addChild($formChild, $associationName);
                }
            }
        }
    }
    
    public function addChildToContainerFromDefinition(\muuska\html\ChildrenContainer $container, $field, $fieldDefinition, \muuska\dao\DAO $dao, $model, $loadedModel = null, $inputName = null, $externalFieldDefinition = array(), \muuska\validation\result\ModelValidationResult $validationResult = null, $defaultValue = null)
    {
        if(!isset($fieldDefinition['editingDisabled']) || !$fieldDefinition['editingDisabled']){
            $field = $this->createFormChildFromDefinition($fieldDefinition, $field, $dao, $model, $loadedModel, $inputName, $externalFieldDefinition, $validationResult, $defaultValue);
            if($field !== null){
                $container->addChild($field);
            }
        }
    }
    
    public function createFormChildFromDefinition($fieldDefinition, $field, \muuska\dao\DAO $dao, $model, $loadedModel = null, $inputName = '', $externalFieldDefinition = null, \muuska\validation\result\ModelValidationResult $validationResult = null, $defaultValue = null)
    {
        $formChild = null;
        $modelDefinition = $dao->getModelDefinition();
        $label = $this->translateModel($modelDefinition, $field, 'form');
        $nature = isset($fieldDefinition['nature']) ? $fieldDefinition['nature'] : '';
        if(empty($inputName)){
            $inputName = $field;
        }
        if(isset($fieldDefinition['lang']) && $fieldDefinition['lang']){
            $formChild = App::htmls()->createGridTranslatableField($field, $label, $this->input->getLang());
            $languages = $this->input->getLanguages();
            $langValidationResult = ($validationResult !== null) ? $validationResult->getLangFieldValidationResult($field) : null;
            foreach ($languages as $language) {
                $languageUniqueCode = $language->getUniqueCode();
                $langInputName = $inputName.'['.$languageUniqueCode.']';
                $langInput = $this->createInputFromDefinition($fieldDefinition, $field, $langInputName, $dao, $model, $loadedModel, true, $languageUniqueCode, $defaultValue);
                $newChild = App::htmls()->createFormField($languageUniqueCode, App::createHtmlLabel($language->getDisplayName($this->input->getLang())), $langInput);
                if($langValidationResult !== null){
                    $fieldResult = $langValidationResult->getLangResult($languageUniqueCode);
                    if(($fieldResult !== null) && !$fieldResult->isValid()){
                        $newChild->setError(implode(', ', $fieldResult->getErrors()));
                    }
                }
                $formChild->addChild($newChild);
            }
            if($langValidationResult !== null){
                $formChild->setError(implode(', ', $langValidationResult->getErrors()));
            }
            if(isset($fieldDefinition['required']) && $fieldDefinition['required']){
                $formChild->setRequired(true);
            }
        }elseif($nature==FieldNature::EXISTING_MODEL_ID){
            $label = isset($externalFieldDefinition['label']) ? $externalFieldDefinition['label'] : $label;
            $formChild = $this->createExternalChildFromDefinition($fieldDefinition, $field, $label, $dao, $model, $loadedModel, $inputName, $externalFieldDefinition, $validationResult, $defaultValue);
        }else{
            $input = $this->createInputFromDefinition($fieldDefinition, $field, $inputName, $dao, $model, $loadedModel, false, null, $defaultValue);
            $formChild = App::htmls()->createGridFormField($field, App::createHtmlLabel($label), $input);
            if($validationResult !== null){
                $fieldResult = $validationResult->getFieldResult($field);
                if(($fieldResult !== null) && !$fieldResult->isValid()){
                    $formChild->setError(implode(', ', $fieldResult->getErrors()));
                }
            }
            if(isset($fieldDefinition['required']) && $fieldDefinition['required']){
                $formChild->setRequired(true);
            }
        }
        
        return $formChild;
    }
    
    public function createExternalChildFromDefinition($fieldDefinition, $field, $label, \muuska\dao\DAO $dao, $model, $loadedModel = null, $inputName = '', $externalFieldDefinition = null, \muuska\validation\result\ModelValidationResult $validationResult = null, $defaultValue = null)
    {
        $formChild = null;
        $input = null;
        $editionType = isset($externalFieldDefinition['editionType']) ? $externalFieldDefinition['editionType'] : null;
        if(empty($editionType)){
            $externalDao = $dao->getForeignDAO($field);
            $externalModelDefinition = $externalDao->getModelDefinition();
            if($externalModelDefinition->containsField($externalModelDefinition->getParentField())){
                $editionType = ExternalFieldEditionType::TREE;
            }else{
                $editionType =  ExternalFieldEditionType::SELECT;
            }
        }
        if($editionType == ExternalFieldEditionType::SELECT2){
            $externalDao = $dao->getForeignDAO($field);
            $data = $externalDao->getData($this->input->createSelectionConfig());
            $value = $this->getFieldValue($field, $fieldDefinition, $dao, $model, false, null, $defaultValue);
            $input = App::htmls()->createSelect2($field, $data->toOptionProvider(), $value, true);
        }elseif($editionType == ExternalFieldEditionType::SELECT){
            $externalDao = $dao->getForeignDAO($field);
            $data = $externalDao->getData($this->input->createSelectionConfig());
            $value = $this->getFieldValue($field, $fieldDefinition, $dao, $model, false, null, $defaultValue);
            $input = App::htmls()->createSelect($field, $data->toOptionProvider(), $value, true);
        }elseif($editionType == ExternalFieldEditionType::TREE){
            $externalDao = $dao->getForeignDAO($field);
            $externalModelDefinition = $externalDao->getModelDefinition();
            $selectionConfig = $this->input->createSelectionConfig();
            $selectionConfig->addRestrictionFieldFromParams($externalModelDefinition->getParentField(), null);
            $data = $externalDao->getData($selectionConfig);
            $input = App::htmls()->createHtmlTree($data);
            $input->createTitleField(App::renderers()->createSimpleValueRenderer(App::getters()->createModelPresentationGetter($externalModelDefinition)));
            $input->setItemSelectorEnabled(true);
            $input->setIdentifierGetter(App::getters()->createModelIdentifierGetter($externalModelDefinition));
            $input->setSubValuesGetter(App::getters()->createChildrenModelGetter($externalDao, $this->input->createSelectionConfig()));
            $value = $this->getFieldValue($field, $fieldDefinition, $dao, $model, false, null, $defaultValue);
            $input->setOnlyOneItemSelectable(true);
            if($value !== null){
                $input->setSelectedIds(array($value));
            }
            $input->setIdentifier($inputName);
        }elseif($editionType == ExternalFieldEditionType::ALL_FIELDS) {
            $formChild = $this->createAllExternalChildrenFromDefinition($fieldDefinition, $field, $label, $inputName, $externalFieldDefinition, $dao, $model, $loadedModel, $validationResult);
        }
        
        if($formChild === null){
            if($input === null){
                $value = $this->getFieldValue($field, $fieldDefinition, $dao, $model, false, null, $defaultValue);
                $input = App::htmls()->createHtmlInput('text', $inputName, $value);
            }
            $formChild = App::htmls()->createGridFormField($field, App::createHtmlLabel($label), $input);
            if($validationResult !== null){
                $fieldResult = $validationResult->getFieldResult($field);
                if(($fieldResult !== null) && !$fieldResult->isValid()){
                    $formChild->setError(implode(', ', $fieldResult->getErrors()));
                }
            }
            if(isset($fieldDefinition['required']) && $fieldDefinition['required']){
                $formChild->setRequired(true);
            }
        }
        return $formChild;
    }
    
    public function createAllExternalChildrenFromDefinition($fieldDefinition, $field, $label, $inputName, $externalFieldDefinition, \muuska\dao\DAO $dao, $model, $loadedModel = null, \muuska\validation\result\ModelValidationResult $validationResult = null)
    {
        $externalDao = $dao->getForeignDAO($field);
        $modelDefinition = $dao->getModelDefinition();
        $externalModelDefinition = $externalDao->getModelDefinition();
        
        $newModel = null;
        $newLoadedModel = null;
        $newValidationResult = null;
        if($model !== null){
            $newModel = $modelDefinition->getAssociatedModel($model, $field);
        }
        if($loadedModel !== null){
            $newLoadedModel = $modelDefinition->getAssociatedModel($loadedModel, $field);
        }
        if($validationResult !== null){
            $newValidationResult = $validationResult->getAssociatedModelResult($field);
        }
        
        $container = App::htmls()->createChildrenContainer();
        $externalFieldDefinitions = $externalModelDefinition->getFieldDefinitions();
        foreach ($externalFieldDefinitions as $externalField => $externalModelFieldDefinition) {
            if(!isset($externalFieldDefinition['excludedFields']) || !in_array($externalField, $externalFieldDefinition['excludedFields'])){
                $subExternalFieldDefinition = null;
                if(isset($externalFieldDefinition['externalFieldsDefinition']) && isset($externalFieldDefinition['externalFieldsDefinition'][$externalField])){
                    $subExternalFieldDefinition = $externalFieldDefinition['externalFieldsDefinition'][$externalField];
                }
                $defaultValue = isset($externalFieldDefinition['defaultValue']) ? $externalFieldDefinition['defaultValue'] : null;
                $externalInputName = $inputName.'_'.$externalField;
                $this->addChildToContainerFromDefinition($container, $externalField, $externalModelFieldDefinition, $externalDao, $newModel, $newLoadedModel, $externalInputName, $subExternalFieldDefinition, $newValidationResult, $defaultValue);
            }
        }
        /*$formChild = App::htmls()->createGridFormField($field, App::createHtmlLabel($label), $container);
        if(isset($fieldDefinition['required']) && $fieldDefinition['required']){
            $formChild->setRequired(true);
        }
        return $formChild;*/
        $container->setLabel($label);
        return $container;
    }
    
    public function getFieldValue($field, $fieldDefinition, \muuska\dao\DAO $dao, $model, $translatableField = false, $lang = null, $defaultValue = null) {
        $value = null;
        $modelDefinition = $dao->getModelDefinition();
        if(!$this->isUpdate() && !$this->isSubmitted()){
            if($defaultValue !== null){
                $value = $defaultValue;
            }elseif(isset($fieldDefinition['default'])){
                $value = $fieldDefinition['default'];
            }
        }elseif($model !== null){
            if($translatableField){
                $value = $modelDefinition->getPropertyValueByLang($model, $field, $lang);
            }else{
                $value = $modelDefinition->getPropertyValue($model, $field);
            }
        }
        return $value;
    }
    public function createInputFromDefinition($fieldDefinition, $field, $inputName, \muuska\dao\DAO $dao, $model, $loadedModel = null, $translatableField = false, $lang = null, $defaultValue = null)
    {
        $modelDefinition = $dao->getModelDefinition();
        $renderer = null;
        $value = $this->getFieldValue($field, $fieldDefinition, $dao, $model, $translatableField, $lang, $defaultValue);
        $nature = isset($fieldDefinition['nature']) ? $fieldDefinition['nature'] : '';
        if($fieldDefinition['type']==DataType::TYPE_BOOL){
            $renderer = App::htmls()->createRadioSwitch($inputName, App::options()->createBoolProvider($this->input->getLang()), $value);
        }elseif($nature==FieldNature::EMAIL){
            $renderer = App::htmls()->createHtmlInput('text', $inputName, $value);
        }elseif($nature==FieldNature::PASSWORD){
            $renderer = App::htmls()->createHtmlInput('password', $inputName);
        }elseif($fieldDefinition['type']==DataType::TYPE_DATE){
            $language = $this->input->getLanguageInfo();
            $renderer = App::htmls()->createHtmlInput('text', $inputName, $value);
            $renderer->convertToDatePicker((($language !== null) ? $language->getLanguage() : null));
        }elseif($fieldDefinition['type']==DataType::TYPE_DATETIME){
            $renderer = App::htmls()->createHtmlInput('text', $inputName, $value);
            $language = $this->input->getLanguageInfo();
            $renderer->convertToDateTimePicker((($language !== null) ? $language->getLanguage() : null));
        }elseif($nature==FieldNature::HTML){
            $renderer = App::htmls()->createRichTextEditor($inputName, $value);
        }elseif($nature==FieldNature::LONG_TEXT){
            $renderer = App::htmls()->createTextarea($inputName, $value);
        }elseif(($nature == FieldNature::IMAGE) || ($nature == FieldNature::FILE)){
            $detailsSavingEnabled = isset($fieldDefinition['detailsSavingEnabled']) ? $fieldDefinition['detailsSavingEnabled'] : false;
            $renderer = App::htmls()->createFileUpload($this->urlCreator->createUrl('upload'), false, '');
            $renderer->setDeleteUrl($this->urlCreator->createUrl('delete-upload'));
            $renderer->setPreviewTemplate(App::htmls()->createUploadPreview($inputName, null, '', false, true));
            $allowedExtensions = App::getFileTools()->getAllowedExtensions($fieldDefinition);
            if(!empty($allowedExtensions)){
                $renderer->setAllowedExtensions($allowedExtensions);
                if($nature == FieldNature::IMAGE){
                    $renderer->setAccept('image/*');
                }else{
                    $renderer->setAccept(implode(',', $allowedExtensions));
                }
            }
            if (isset($fieldDefinition['excludedExtensions'])) {
                $renderer->setExcludedExtensions($fieldDefinition['excludedExtensions']);
            }
            $mainApplication = App::getApp();
            $createSavedPreview = false;
            if(!$this->isSubmitted() && ($loadedModel !== null) && ($modelDefinition->isLoaded($loadedModel))){
                $createSavedPreview = true;
            }elseif($this->isSubmitted()) {
                $fileName = $modelDefinition->getPropertyValue($model, $field);
                if(!empty($fileName)){
                    if(($loadedModel !== null) && ($modelDefinition->isLoaded($loadedModel))){
                        $createSavedPreview = ($fileName === $modelDefinition->getPropertyValue($loadedModel, $field));
                    }
                    if(!$createSavedPreview){
                        $fileUrl = $mainApplication->getUploadTmpFullUrl($fileName);
                        $fileLocation = $mainApplication->getUploadTmpFullFile($fileName);
                        $filePreview = $mainApplication->getFilePreview($this->input->getSubAppName(), $fileUrl, $fileLocation, $fileName, $detailsSavingEnabled, null);
                        $previewItem = App::htmls()->createUploadPreview($inputName, $fileName, $filePreview, true);
                        $renderer->addPreview($previewItem);
                    }
                }
            }
            if($createSavedPreview){
                $fileValue = $modelDefinition->getPrimaryValue($loadedModel);
                $fileName = $modelDefinition->getPropertyValue($loadedModel, $field);
                if(!empty($fileName)){
                    $fileUrl = $mainApplication->getModelFileUrl($modelDefinition, $loadedModel, $field);
                    $fileLocation = $mainApplication->getModelFullFile($modelDefinition, $loadedModel, $field);
                    $filePreview = $mainApplication->getFilePreview($this->input->getSubAppName(), $fileUrl, $fileLocation, $fileName, $detailsSavingEnabled, null);
                    $previewItem = App::htmls()->createUploadPreview('saved_'.$inputName, $fileValue, $filePreview, true);
                    $renderer->addPreview($previewItem);
                }
            }
        }elseif(($nature == FieldNature::OBJECT_STATE) || ($nature == FieldNature::OPTION)){
            if(isset($fieldDefinition['optionProvider'])){
                $renderer = App::htmls()->createSelect($inputName, $fieldDefinition['optionProvider']->getLangOptionProvider($this->input->getLang()), $value, true);
            }
        }else{
            $renderer = App::htmls()->createHtmlInput('text', $inputName, $value);
        }
        
        return $renderer;
    }
    
    /**
     * @param boolean $onlySave
     * @return \muuska\util\NavigationResult
     */
    public function run($onlySave = false) {
        $saveProceed = false;
        $saved = false;
        $content = null;
        
        $submitted = $this->isSubmitted();
        $continue = true;
        $modelValidationResult = null;
        if($submitted){
            $saveProceed = true;
            if($continue){
                $this->retrieveSubmittedData();
                $saved = false;
                try {
                    $saved = $this->saveData();
                    $this->afterSave($saved);
                    if(!$saved){
                        $this->errors[] = $this->l('An error occurred while saving');
                    }
                } catch (\muuska\dao\exception\InvalidModelException $e) {
                    $modelValidationResult = $e->getModelValidationResult();
                    $errors = $modelValidationResult->getErrors();
                    if(!empty($errors)){
                        $this->errors = array_merge($errors);
                    }
                }
            }
        }
        if($continue && !$saved && !$onlySave){
            if($continue){
                $content = $this->renderForm($modelValidationResult);
            }
        }
        return $this->createResult($saveProceed, $saved, $content);
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
    
    public function addExternalFieldDefinition($field, $editionType, $excludedFields = null, $subExternalFieldsDefinition = null)
    {
        if(empty($excludedFields)){
            $excludedFields = array();
        }
        $excludedFields = array_merge($excludedFields, array('creationDate', 'lastModifiedDate', 'deleted'));
        $definition = array('editionType' => $editionType, 'excludedFields' => $excludedFields, 'excludedFields' => $excludedFields);
        if(!empty($subExternalFieldsDefinition)){
            $definition['externalFieldsDefinition'] = $subExternalFieldsDefinition;
        }
        
        $this->addExternalFieldDefinitionFromArray($field, $definition);
    }
    
    public function addMultipleAssociation($associationName, $field, $label = null, $extraRestrictions = null)
    {
        $this->multipleAssociationsDefinition[$associationName] = array('field' => $field);
        if(!empty($label)){
            $this->multipleAssociationsDefinition[$associationName]['label'] = $label;
        }
        if(!empty($extraRestrictions)){
            $this->multipleAssociationsDefinition[$associationName]['extraRestrictions'] = $extraRestrictions;
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
     * @return array
     */
    public function getDefaultValues()
    {
        return $this->defaultValues;
    }

    /**
     * @return \muuska\dao\util\SaveConfig
     */
    public function getSaveConfig()
    {
        return $this->saveConfig;
    }

    /**
     * @param string $field
     * @param mixed $value
     */
    public function addDefaultValue($field, $value){
        $this->defaultValues[$field] = $value;
    }
    
    /**
     * @param array $values
     */
    public function addDefaultValues($values){
        if (is_array($values)) {
            foreach ($values as $field => $value) {
                $this->addDefaultValue($field, $value);
            }
        }
    }
    
    /**
     * @param array $defaultValues
     */
    public function setDefaultValues($defaultValues)
    {
        $this->defaultValues = $defaultValues;
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
    public function getDefaultModel()
    {
        return $this->defaultModel;
    }

    /**
     * @return object
     */
    public function getLoadedModel()
    {
        return $this->loadedModel;
    }

    /**
     * @return \muuska\dao\util\SelectionConfig
     */
    public function getLoadedModelSelectionConfig()
    {
        return $this->loadedModelSelectionConfig;
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
     * @return boolean
     */
    public function isCrudSubmitButtonEnabled()
    {
        return $this->crudSubmitButtonEnabled;
    }

    /**
     * @param boolean $crudSubmitButtonEnabled
     */
    public function setCrudSubmitButtonEnabled($crudSubmitButtonEnabled)
    {
        $this->crudSubmitButtonEnabled = $crudSubmitButtonEnabled;
    }
}