<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\AssociationDefinition;
use muuska\constants\ExternalFieldEditionType;
use muuska\constants\ExternalFieldViewType;

class AssociationAdminController extends CrudController
{

    protected function onCreate()
    {
        $this->modelDefinition = AssociationDefinition::getInstance();
    }

    protected function createFormHelper($update)
    {
        $helper = parent::createFormHelper($update);

        $helper->addExternalFieldDefinition('membreId', ExternalFieldEditionType::SELECT2);
        
        
        return $helper;
    }

    protected function createViewHelper()
    {
        $helper = parent::createViewHelper();
        
        $helper->setInnerNavigationEnabled(true);
        $helper->addControllerInnerNavigation('membre', $this->l('Membres'), 'association');
        return $helper;
    }
   
    
}
