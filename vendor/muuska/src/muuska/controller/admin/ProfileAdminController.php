<?php
namespace muuska\controller\admin;

use muuska\controller\CrudController;
use muuska\project\constants\SubAppName;
use muuska\security\model\GroupDefinition;

class ProfileAdminController extends CrudController
{	
	protected function onCreate()
    {
        parent::onCreate();
        $this->modelDefinition = GroupDefinition::getInstance();
    }
    
    protected function createListHelper() {
        $listHelper = parent::createListHelper();
        $listHelper->setCurrentControllerInfoEnabled(true);
        $selectionConfig = $listHelper->getSelectionConfig();
        $selectionConfig->addRestrictionFieldFromParams('subAppName', SubAppName::BACK_OFFICE);
        $listHelper->addExcludedField('subAppName');
        $listHelper->setTitle($this->l('Profiles'));
        return $listHelper;
    }
    
    protected function createFormHelper($update){
        $helper = parent::createFormHelper($update);
        $helper->addMultipleAssociation('accesses', 'resourceId');
        if($update){
            $helper->initLoadedModelSelectionConfig();
            $selectionConfig = $helper->getLoadedModelSelectionConfig();
            $selectionConfig->addRestrictionFieldFromParams('subAppName', SubAppName::BACK_OFFICE);
        }
        $helper->addDefaultValue('subAppName', SubAppName::BACK_OFFICE);
        $helper->addExcludedField('subAppName');
        return $helper;
    }
    
    protected function createViewHelper(){
        $helper = parent::createViewHelper();
        $helper->addMultipleAssociation('accesses', 'resourceId');
        $helper->initSelectionConfig();
        $selectionConfig = $helper->getSelectionConfig();
        $selectionConfig->addRestrictionFieldFromParams('subAppName', SubAppName::BACK_OFFICE);
        $helper->addExcludedField('subAppName');
        return $helper;
    }
}