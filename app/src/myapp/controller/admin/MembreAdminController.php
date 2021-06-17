<?php
namespace myapp\controller\admin;

use muuska\constants\ExternalFieldEditionType;
use muuska\controller\CrudController;
use muuska\util\App;
use myapp\model\MembreDefinition;
use myapp\model\AssociationDefinition;

class MembreAdminController extends CrudController
{
    protected function onCreate() {
        $this->modelDefinition = MembreDefinition::getInstance();
    }
   
    
    protected function createFormHelper($update)
    {
        $helper = parent::createFormHelper($update);
        
        return $helper;
    }
    protected function createViewHelper()
    {
        $helper = parent::createViewHelper();
        $helper->setInnerNavigationEnabled(true);
        $helper->addControllerInnerNavigation('epargne', $this->l('Epargnes'), 'epargne');
        $helper->addControllerInnerNavigation('emprunt', $this->l('Emprunts'), 'emprunt');
        $helper->addControllerInnerNavigation('remboursement', $this->l('Remboursements'), 'remboursement');
        return $helper;
    }
    
    protected function createListHelper()
    {
        $helper = parent::createListHelper();
        $helper->addExcludedFields(array('id'));
        return $helper;
    }
}
