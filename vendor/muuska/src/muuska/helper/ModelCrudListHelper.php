<?php
namespace muuska\helper;

use muuska\constants\ActionCode;
use muuska\constants\DataType;
use muuska\html\constants\ButtonStyle;
use muuska\html\constants\ListType;
use muuska\util\App;

class ModelCrudListHelper extends ModelListHelper
{
    public function addMainAction(\muuska\html\panel\ListPanel $listPanel, $name, $label, $action, \muuska\html\HtmlContent $icon = null, $buttonStyleEnabled = false, $style = null, $styleClass = null, $class = null, $title = null, $confirm = false, $confirmText = null, $openMode = null, $ajaxDisabled = false){
        $actionObject = null;
        $url = null;
        if($this->listType == ListType::TREE){
            $url = $this->urlCreator->createControllerUrl($this->input->getName(), $action);
        }else{
            $url = $this->urlCreator->createUrl($action);
        }
        
        if(empty($title)){
            $title = $label;
        }
        $actionObject = App::htmls()->createHtmlLink(App::createHtmlString($label), $url, $icon, $title, $buttonStyleEnabled, $style);
        $actionObject->addAttribute('data-action', $action);
        $actionObject->setName($name);
        $actionObject->addClass('main_action');
        $this->formatAction($actionObject, $action, $class, $buttonStyleEnabled, $styleClass, $confirm, $confirmText, $openMode, $ajaxDisabled);
        if ($this->isActionEnabled($action)) {
            $listPanel->addTool($actionObject);
        }
        return $actionObject;
    }
    
    public function formatAction(\muuska\html\command\HtmlCommand $actionObject, $action, $class, $buttonStyleEnabled, $styleClass, $confirm, $confirmText, $openMode, $ajaxDisabled){
        if(!empty($class)){
            $actionObject->addClassesFromString($class);
        }
        if($buttonStyleEnabled && empty($styleClass)){
            $actionObject->addStyleClass(ButtonStyle::DEFAULT_STYLE);
        }
        if(!empty($styleClass)){
            $actionObject->addStyleClassesFromString($styleClass);
        }
        if($confirm){
            $actionObject->setConfirm(true);
            $actionObject->setConfirmText($confirmText);
        }
        if($ajaxDisabled){
            $actionObject->disableAjax();
        }
        if($openMode){
            $actionObject->setOpenMode($openMode);
        }
    }
    
    public function addItemAction(\muuska\html\listing\AbstractList $list, $name, $label, $action = null, \muuska\html\HtmlContent $icon = null, $buttonStyleEnabled = false, $styleClass = null, $class = null, $title = null, $confirm = false, $confirmText = null, $openMode = null, $ajaxDisabled = false){
        $modelUrlCreator = $this->urlCreator->createModelUrlCreator($this->dao->getModelDefinition(), $action);
        $modelUrlCreator->setCurrentControllerNameEnabled($this->currentControllerInfoEnabled);
        if(empty($title)){
            $title = $label;
        }
        $actionObject = App::htmls()->createItemAction($action, $modelUrlCreator, App::createHtmlString($label), $icon, $title, $buttonStyleEnabled);
        $actionObject->addClass('item_action');
        $this->formatAction($actionObject, $action, $class, $buttonStyleEnabled, $styleClass, $confirm, $confirmText, $openMode, $ajaxDisabled);
        if ($this->isActionEnabled($action)) {
            $list->addAction($actionObject);
        }
        return $actionObject;
    }
    public function createItemAction(\muuska\html\listing\AbstractList $list, $action, $definition, \muuska\url\objects\ObjectUrl $modelUrlCreator) {
        $action = isset($definition['action']) ? $definition['action'] : '';
        $label = isset($definition['label']) ? $definition['label'] : '';
        $icon = isset($definition['icon']) ? App::createFAIcon($definition['icon']) : null;
        $buttonStyleEnabled = isset($definition['buttonStyleEnabled']) ? $definition['buttonStyleEnabled'] : false;
        $confirm = isset($definition['confirm']) ? $definition['confirm'] : false;
        $title = isset($definition['title']) ? $definition['title'] : $label;
        $confirmText = (isset($definition['confirmText']) && !empty($definition['confirmText'])) ? $definition['confirmText'] : $this->createConfirmText($action);
        $class = isset($definition['class']) ? $definition['class'] : '';
        $styleClass = isset($definition['styleClass']) ? $definition['styleClass'] : '';
        $openMode = isset($definition['openMode']) ? $definition['openMode'] : '';
        $ajaxDisabled = isset($definition['ajaxDisabled']) ? $definition['ajaxDisabled'] : false;
        $actionObject = App::htmls()->createItemAction($action, $modelUrlCreator, App::createHtmlString($label), $icon, $title, $buttonStyleEnabled);
        $actionObject->addClass('item_action');
        $this->formatAction($actionObject, $action, $class, $buttonStyleEnabled, $styleClass, $confirm, $confirmText, $openMode, $ajaxDisabled);
        if ($this->isActionEnabled($action)) {
            $list->addAction($actionObject);
        }
        return $actionObject;
    }
    
    public function addCrudMainActions(\muuska\html\panel\ListPanel $listPanel) {
        $this->addMainAction($listPanel, ActionCode::ADD, $this->l('Add new'), ActionCode::ADD, App::createFAIcon('plus'), true, ButtonStyle::PRIMARY);
    }
    
    public function addCrudItemActions(\muuska\html\listing\AbstractList $list, $excludedActions = array()) {
        if(($this->listType == ListType::TREE) && !in_array(ActionCode::ADD, $excludedActions)){
            $addUrlCreator = App::urls()->createDefaultObjectUrl(function($data, $params = array(), $anchor = '', $mode = null){
                return $this->urlCreator->createUrl(ActionCode::ADD, array('parent' => $this->dao->getModelDefinition()->getSinglePrimaryValue($data)));
            });
            $actionObject = App::htmls()->createItemAction(ActionCode::ADD, $addUrlCreator, App::createHtmlString($this->l('Add')), App::createFAIcon('plus'), $this->l('Add sub items'));
            $actionObject->addClass('item_action');
            if ($this->isActionEnabled(ActionCode::ADD)) {
                $list->addAction($actionObject);
            }
        }
        if(!in_array(ActionCode::VIEW, $excludedActions)){
            $this->addItemAction($list, ActionCode::VIEW, $this->l('View'), ActionCode::VIEW, App::createFAIcon('search-plus'));
        }
        if(!in_array(ActionCode::UPDATE, $excludedActions)){
            $this->addItemAction($list, ActionCode::UPDATE, $this->l('Update'), ActionCode::UPDATE, App::createFAIcon('pen'));
        }
        $modelDefinition = $this->dao->getModelDefinition();
        if($modelDefinition->containsField($modelDefinition->getActivationField())){
            if(!in_array(ActionCode::ACTIVATE, $excludedActions)){
                $this->addItemAction($list, ActionCode::ACTIVATE, $this->l('Activate'), ActionCode::ACTIVATE);
            }
            if(!in_array(ActionCode::DEACTIVATE, $excludedActions)){
                $this->addItemAction($list, ActionCode::DEACTIVATE, $this->l('Deactivate'), ActionCode::DEACTIVATE);
            }
            
            if(!$list->hasItemCreator()){
                $itemCreator = App::htmls()->createDefaultListItemCreator(function($data, \muuska\html\listing\item\ListItemContainer $listItemContainer, \muuska\html\config\HtmlGlobalConfig $globalConfig, \muuska\html\config\caller\HtmlCallerConfig $callerConfig = null){
                    $item = $listItemContainer->defaultCreateItem($data, $globalConfig, $callerConfig);
                    $modelDefinition = $this->dao->getModelDefinition();
                    if($modelDefinition->getPropertyValue($data, $modelDefinition->getActivationField())){
                        $item->addDisabledAction(ActionCode::ACTIVATE);
                    }else{
                        $item->addDisabledAction(ActionCode::DEACTIVATE);
                    }
                    return $item;
                });
                $list->setItemCreator($itemCreator);
            }
            
        }
        if(!in_array(ActionCode::DELETE, $excludedActions)){
            $this->addItemAction($list, ActionCode::DELETE, $this->l('Delete'), ActionCode::DELETE, App::createFAIcon('trash-alt'), false, null, null, null, true, $this->l('Are you sure to delete this record ?'));
        }
    }

    public function createBulkActions(\muuska\html\panel\ListPanel $listPanel)
    {
        $container = App::htmls()->createButtonGroup();
        $container->addClass('bulk_action_area');
        $modelDefinition = $this->dao->getModelDefinition();
        if ($modelDefinition->containsField($modelDefinition->getActivationField())) {
            $statusDropdown = App::htmls()->createDropdown(App::createHtmlString($this->l('Update status')));
            $this->createBulkAction($statusDropdown, ActionCode::ACTIVATE, $this->l('Activate'));
            $this->createBulkAction($statusDropdown, ActionCode::DEACTIVATE, $this->l('Deactivate'));
            if ($statusDropdown->hasChildren()) {
                $container->addChild($statusDropdown, 'status');
            }
        }
        $stateField = $modelDefinition->getStateField();
        if ($modelDefinition->containsField($stateField) && $this->isActionEnabled(ActionCode::CHANGE_SATE)) {
            $stateDefinition = $modelDefinition->getFieldDefinition($stateField);
            if(isset($stateDefinition['optionProvider'])){
                $stateDropdown = App::htmls()->createDropdown(App::createHtmlString($this->l('Update state')));
                $options = $stateDefinition['optionProvider']->getLangOptionProvider($this->input->getLang())->getOptions();
                foreach ($options as $option) {
                    $label = $option->getLabel();
                    $value = $option->getValue();
                    $result = App::htmls()->createHtmlLink(App::createHtmlString($label), $this->urlCreator->createUrl(ActionCode::CHANGE_SATE, array('bulk' => 1)), null, $label);
                    $result->addClass('bulk_action');
                    $stateDropdown->addChild($result, 'state_'.$value);
                }
                if ($stateDropdown->hasChildren()) {
                    $container->addChild($stateDropdown, 'state');
                }
            }
        }
        $deleteAction = $this->createBulkAction($container, ActionCode::DELETE, $this->l('Delete'), App::createFAIcon('trash-alt'), '', true, ButtonStyle::SECONDARY);
        if($deleteAction !== null){
            $deleteAction->setConfirm(true);
            $deleteAction->setConfirmText($this->l('Are you sure to delete %d selected records ?'));
        }
        if ($container->hasChildren()) {
            $listPanel->setBulkActionArea($container);
            $container->setVisible(false);
        }
        return $container;
    }
    
    public function createBulkAction(\muuska\html\AbstractChildWrapper $wrapper, $action, $label, \muuska\html\HtmlContent $icon = null, $title = '', $buttonStyleEnabled = false, $style = null)
    {
        $result = null;
        if($this->isActionEnabled($action)){
            if(empty($title)){
                $title = $label;
            }
            $result = App::htmls()->createHtmlLink(App::createHtmlString($label), $this->urlCreator->createUrl($action, array('bulk' => 1)), $icon, $title, $buttonStyleEnabled, $style);
            $result->addClass('bulk_action');
            $wrapper->addChild($result, $action);
        }
        return $result;
    }
    
    public function createFields(\muuska\html\listing\AbstractList $list, \muuska\html\ChildrenContainer $specificSearchContainer = null, \muuska\html\AbstractChildWrapper $specificSortContainer = null, $searchData = array(), $activeSortField = null, $activeSortDirection = null)
    {
        $modelDefinition = $this->dao->getModelDefinition();
        if($this->listType == ListType::TREE){
            $list->createTitleField(App::renderers()->createSimpleValueRenderer(App::getters()->createModelPresentationGetter($modelDefinition)));
        }else{
            $excludedFields = $this->excludedFields;
            
            $primaries = $modelDefinition->getPrimaries();
            foreach($primaries as $field){
                if(!$modelDefinition->containsField($field) && !in_array($field, $excludedFields)){
                    $this->createFieldFromDefinition($list, $field, array('type' => DataType::TYPE_INT), $this->translateModel($modelDefinition, $field, 'list'), $this->dao, $field, $specificSearchContainer, $specificSortContainer, $searchData, $activeSortField, $activeSortDirection);
                }
            }
            
            $fields = $modelDefinition->getFieldDefinitions();
            foreach($fields as $field => $fieldDefinition){
                if(!in_array($field, $excludedFields)){
                    $externalFieldDefinition = isset($this->externalFieldsDefinition[$field]) ? $this->externalFieldsDefinition[$field] : null;
                    $label = isset($externalFieldDefinition['label']) ? $externalFieldDefinition['label'] : $this->translateModel($modelDefinition, $field, 'list');
                    $fieldKey = isset($externalFieldDefinition['fieldKey']) ? $externalFieldDefinition['fieldKey'] : $field;
                    $this->createFieldFromDefinition($list, $field, $fieldDefinition, $label, $this->dao, $fieldKey, $specificSearchContainer, $specificSortContainer, $searchData, $activeSortField, $activeSortDirection, null, $externalFieldDefinition, null);
                }
            }
        }
    }
}