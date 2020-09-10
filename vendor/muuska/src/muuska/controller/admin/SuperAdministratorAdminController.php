<?php
namespace muuska\controller\admin;

use muuska\controller\CrudController;
use muuska\project\constants\SubAppName;
use muuska\security\model\AuthentificationDefinition;

class SuperAdministratorAdminController extends CrudController
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
        $selectionConfig->addRestrictionFieldFromParams('superUser', 1);
        $selectionConfig->addRestrictionFieldFromParams('subAppName', SubAppName::BACK_OFFICE);
        $listHelper->addExcludedField('subAppName');
        $listHelper->addExcludedField('superUser');
        $listHelper->setTitle($this->l('Super administrators'));
        return $listHelper;
    }
    
    protected function createFormHelper($update){
        $helper = parent::createFormHelper($update);
        if($update){
            $helper->initLoadedModelSelectionConfig();
            $selectionConfig = $helper->getLoadedModelSelectionConfig();
            $selectionConfig->addRestrictionFieldFromParams('superUser', 1);
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
        $helper->initSelectionConfig();
        $selectionConfig = $helper->getSelectionConfig();
        $selectionConfig->addRestrictionFieldFromParams('superUser', 1);
        $selectionConfig->addRestrictionFieldFromParams('subAppName', SubAppName::BACK_OFFICE);
        $helper->addExcludedField('subAppName');
        $helper->addExcludedField('superUser');
        return $helper;
    }
}