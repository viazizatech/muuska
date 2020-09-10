<?php
namespace muuska\controller;

use muuska\util\App;
use muuska\http\constants\RedirectionType;
use muuska\html\constants\ListType;

abstract class CrudController extends AbstractController
{
	/**
	 * @var \muuska\model\ModelDefinition
	 */
	protected $modelDefinition;
	
	protected function initParamResolver()
	{
	    if($this->modelDefinition->containsField($this->modelDefinition->getParentField())){
	        $this->paramResolver = App::controllers()->createDefaultControllerParamResolver($this->input, $this->result, array(App::controllers()->createModelControllerParamParser($this->modelDefinition, 'parent', false, array('modelField' => $this->modelDefinition->getParentField(), 'noRestriction' => true))));
	    }else{
	        parent::initParamResolver();
	    }
	}
	
	protected function processDefault() {
        $listHelper = $this->createListHelper();
        $listHelper->init();
        if($this->input->hasQueryParam('innerFilter')){
            $listHelper->storeInnerSearchDataFromRequest();
            $this->result->setRedirection(App::https()->createDynamicRedirection(RedirectionType::DEFAULT_ACTION));
        }elseif($this->input->hasQueryParam('specificFilter')){
            $listHelper->storeSpecificSearchDataFromRequest();
            $this->result->setRedirection(App::https()->createDynamicRedirection(RedirectionType::DEFAULT_ACTION));
        }elseif($this->input->hasQueryParam('quickFilter')){
            $listHelper->storeQuickSearchDataFromRequest();
            $this->result->setRedirection(App::https()->createDynamicRedirection(RedirectionType::DEFAULT_ACTION));
        }elseif($this->input->hasQueryParam('resetInnerFilter')){
            $listHelper->resetInnerFilters();
            $this->result->setRedirection(App::https()->createDynamicRedirection(RedirectionType::DEFAULT_ACTION));
        }elseif($this->input->hasQueryParam('resetSpecificFilter')){
            $listHelper->resetSpecificFilters();
            $this->result->setRedirection(App::https()->createDynamicRedirection(RedirectionType::DEFAULT_ACTION));
        }elseif($this->input->hasParam('sortList')){
            $listHelper->storeSortInfo($this->input->getParam('field'), $this->input->getParam('direction'));
            $this->result->setRedirection(App::https()->createDynamicRedirection(RedirectionType::DEFAULT_ACTION));
        }else{
            $listPanel = $listHelper->prepareList();
            $this->formatListPanel($listPanel, $listHelper);
            $this->result->setContent($listPanel);
        }
    }
	
	/**
     * @return \muuska\helper\ModelCrudListHelper
     */
    protected function createListHelper() {
        $listHelper = App::helpers()->createModelCrudListHelper($this->input, $this->paramResolver, $this->urlCreator, $this->input->getDAO($this->modelDefinition), $this->getRecorderPrefix());
        $listHelper->setOpenMode($this->input->getQueryParam('actionOpenMode'));
        $listHelper->setAjaxEnabled($this->input->isAjaxRequest());
        if($this->modelDefinition->containsField($this->modelDefinition->getParentField())){
            $listHelper->setListType(ListType::TREE);
            $listHelper->setLimiterEnabled(false);
            $listHelper->setDefaultItemsPerPage(0);
        }
        return $listHelper;
    }
    
    /**
     * @param \muuska\html\panel\ListPanel $listPanel
     * @param \muuska\helper\ModelCrudListHelper $listHelper
     */
    protected function formatListPanel(\muuska\html\panel\ListPanel $listPanel, \muuska\helper\ModelCrudListHelper $listHelper) {
        $listHelper->addCrudMainActions($listPanel);
        $list = $listPanel->getInnerContent();
        $listHelper->addCrudItemActions($list);
    }
    
    protected function processAdd(){
        $this->doEdit(false);
    }
    
    protected function processUpdate(){
        $this->doEdit(true);
    }
	
    protected function doEdit($update = false){
        $formHelper = $this->createFormHelper($update);
        $formHelper->init();
        if($formHelper->hasErrors()){
            $this->result->addErrors($formHelper->getErrors());
        }else{
            $result = $formHelper->run();
            if($result->hasContent()){
                $content = $result->getContent();
                $this->formatFormPanel($content, $formHelper);
                $this->result->setContent($content);
                if($formHelper->hasErrors()){
                    $this->result->addErrors($formHelper->getErrors());
                }
            }
            
            if($result->hasRedirection()){
                $this->result->setRedirection($result->getRedirection());
            }
        }
    }
    
	/**
     * @return \muuska\helper\ModelFormHelper
     */
    protected function createFormHelper($update) {
        $formHelper =App::helpers()->createModelFormHelper($this->input, $this->paramResolver, $this->urlCreator, $this->input->getDAO($this->modelDefinition), $update);
        $formHelper->setOpenMode($this->input->getQueryParam('actionOpenMode'));
        $formHelper->setAjaxEnabled($this->input->isAjaxRequest());
        $formHelper->setCrudSubmitButtonEnabled(true);
        $parentField = $this->modelDefinition->getParentField();
        if($this->modelDefinition->containsField($parentField)){
            $formHelper->addExcludedField($parentField);
            /*$formHelper->addDefaultValue($parentField, $this->input->getQueryParam('parent', null));*/
        }
        return $formHelper;
    }
    
    /**
     * @param \muuska\html\panel\Panel $formPanel
     * @param \muuska\helper\ModelFormHelper $formHelper
     */
    protected function formatFormPanel(\muuska\html\panel\Panel $formPanel, \muuska\helper\ModelFormHelper $formHelper) {
        
    }
    
    protected function processView(){
        $viewHelper = $this->createViewHelper();
        $viewHelper->init();
        $viewPanel = $viewHelper->getPanel();
        $this->formatViewPanel($viewPanel, $viewHelper);
        $this->result->setContent($viewPanel);
    }
    
    /**
     * @return \muuska\helper\ModelCrudViewHelper
     */
    protected function createViewHelper() {
        $viewHelper = App::helpers()->createModelCrudViewHelper($this->input, $this->paramResolver, $this->urlCreator, $this->input->getDAO($this->modelDefinition));
        $viewHelper->setOpenMode($this->input->getQueryParam('actionOpenMode'));
        $viewHelper->setAjaxEnabled($this->input->isAjaxRequest());
        $parentField = $this->modelDefinition->getParentField();
        if($this->modelDefinition->containsField($parentField)){
            $viewHelper->addExcludedField($parentField);
        }
        return $viewHelper;
    }
    
    /**
     * @param \muuska\html\panel\Panel $viewPanel
     * @param \muuska\helper\ModelCrudViewHelper $viewHelper
     */
    protected function formatViewPanel(\muuska\html\panel\Panel $viewPanel, \muuska\helper\ModelCrudViewHelper $viewHelper) {
        
    }
    
    protected function processActivate(){
        if($this->modelDefinition->containsField($this->modelDefinition->getActivationField())){
            $this->doDirectAction(function ($dao, $model){
                return $dao->activate($model);
            });
        }else{
            $this->result->addError($this->input->getFrameworkError('Invalid input'));
        }
    }
    
    protected function processDeactivate(){
        if($this->modelDefinition->containsField($this->modelDefinition->getActivationField())){
            $this->doDirectAction(function ($dao, $model){
                return $dao->deactivate($model);
            });
        }else{
            $this->result->addError($this->input->getFrameworkError('Invalid input'));
        }
    }
    
    protected function processDelete(){
        $this->doDirectAction(function ($dao, $model){
            $deleteConfig = $this->input->createDeleteConfig();
            $deleteConfig->setVirtual($this->modelDefinition->containsField($this->modelDefinition->getVirtualDeletionField()));
            return $dao->delete($model, $deleteConfig);
        });
    }
    
    protected function processUpdateState(){
        $state = (int)$this->input->getQueryParam('state');
        if($this->modelDefinition->containsField($this->modelDefinition->getStateField()) && !empty($state)){
            $this->doDirectAction(function ($dao, $model, $params){
                return $dao->changeValue($model, $this->modelDefinition->getStateField(), $params['state']);
            }, null, array('state' => $state));
        }else{
            $this->result->addError($this->input->getFrameworkError('Invalid input'));
        }
    }
    
    /**
     * @param string $action
     * @param object $model
     * @return boolean
     */
    protected function beforeDirectAction($action, $model){
        return true;
    }
    
    /**
     * @param string $action
     * @param bool $result
     * @param object $model
     */
    protected function afterDirectAction($action, $result, $model){
        
    }
    
    /**
     * @param callable $callback
     * @param string $successCode
     * @param array $params
     */
    protected function doDirectAction($callback, $successCode = '', $params = array()){
        $action = $this->input->getAction();
        $models = $this->prepareDirectActionData(false);
        if(!$this->result->hasErrors()){
            foreach($models as $model){
                $continue = $this->beforeDirectAction($action, $model);
                if($continue && !$this->result->hasErrors()){
                    $result = $callback($this->input->getDAO($this->modelDefinition), $model, $params);
                    $this->afterDirectAction($action,$result, $model);
                    if(!$result){
                        $this->result->addError(sprintf($this->input->getFrameworkError('An error occurred while processing data "%s"'), $this->modelDefinition->getSinglePrimaryValue($model)));
                    }
                }
            }
        }
        if($this->result->hasErrors()){
            $this->processDefault();
        }else{
            $redirection = App::https()->createDynamicRedirection(RedirectionType::DEFAULT_ACTION);
            $redirection->setSuccessCode(empty($successCode) ? $action : $successCode);
            $this->result->setRedirection($redirection);
        }
    }
    
    protected function prepareDirectActionData($langEnabled = false){
        $models = array();
        $ids = $this->input->hasQueryParam('bulk') ? $this->input->getParam('ids') : array($this->input->getQueryParam('id'));
        if(is_array($ids)){
            foreach($ids as $id){
                $model = $this->loadModelById($id, $langEnabled);
                if($model !== null){
                    $models[] = $model;
                }else{
                    $this->result->addError($this->input->getFrameworkError('Object not found'));
                    break;
                }
            }
        }else{
            $this->result->addError($this->input->getFrameworkError('You must select at least an item'));
        }
        return $models;
    }
    
    /**
     * @param mixed $id
     * @param boolean $langEnabled
     * @return object
     */
    protected function loadModelById($id, $langEnabled = true){
        $selectionConfig = $this->input->createSelectionConfig();
        $selectionConfig->createRestrictionFieldsFromArray($this->modelDefinition->getPrimaryValuesFromString($id));
        $selectionConfig->setLangEnabled($langEnabled);
        return $this->input->getDAO($this->modelDefinition)->getUniqueModel($selectionConfig, false);
    }
}