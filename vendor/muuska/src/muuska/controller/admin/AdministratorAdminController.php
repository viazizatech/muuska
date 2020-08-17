<?php
namespace muuska\controller\admin;

use muuska\controller\CrudController;
use muuska\security\model\AuthentificationDefinition;
use muuska\project\constants\SubAppName;

class AdministratorAdminController extends CrudController
{	
	protected function onCreate()
    {
        parent::onCreate();
        $this->modelDefinition = AuthentificationDefinition::getInstance();
    }
    
    protected function createListHelper() {
        $listHelper = parent::createListHelper();
        $listHelper->setCurrentControllerInfoEnabled(true);
        $selectionConfig = $listHelper->getSelectionConfig();
        $selectionConfig->addRestrictionFieldFromParams('superUser', 0);
        $selectionConfig->addRestrictionFieldFromParams('subAppName', SubAppName::BACK_OFFICE);
        $listHelper->addExcludedField('subAppName');
        $listHelper->addExcludedField('superUser');
        $listHelper->setTitle($this->l('Administrators'));
        return $listHelper;
    }
    
    protected function createFormHelper($update){
        $helper = parent::createFormHelper($update);
        $helper->addMultipleAssociation('groups', 'groupId', null, array('subAppName' => SubAppName::BACK_OFFICE));
        $helper->addMultipleAssociation('accesses', 'resourceId');
        if($update){
            $helper->initLoadedModelSelectionConfig();
            $selectionConfig = $helper->getLoadedModelSelectionConfig();
            $selectionConfig->addRestrictionFieldFromParams('superUser', 0);
            $selectionConfig->addRestrictionFieldFromParams('subAppName', SubAppName::BACK_OFFICE);
            $helper->addExcludedField('password');
            $helper->getSaveConfig()->addExcludedField('password');
        }
        $helper->addDefaultValue('superUser', 0);
        $helper->addDefaultValue('subAppName', SubAppName::BACK_OFFICE);
        $helper->addExcludedField('subAppName');
        $helper->addExcludedField('superUser');
        return $helper;
    }
    
    protected function createViewHelper(){
        $helper = parent::createViewHelper();
        $helper->addMultipleAssociation('groups', 'groupId');
        $helper->addMultipleAssociation('accesses', 'resourceId');
        $helper->initSelectionConfig();
        $selectionConfig = $helper->getSelectionConfig();
        $selectionConfig->addRestrictionFieldFromParams('superUser', 0);
        $selectionConfig->addRestrictionFieldFromParams('subAppName', SubAppName::BACK_OFFICE);
        $helper->addExcludedField('subAppName');
        $helper->addExcludedField('superUser');
        return $helper;
    }
}