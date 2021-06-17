<?php
namespace myapp\controller\admin;

use  myapp\controller\admin\partial\CrudAdminController;
use myapp\model\EpargneDefinition;
use muuska\constants\ExternalFieldEditionType;
use muuska\constants\ExternalFieldViewType;
use muuska\util\App;
use myapp\model\MembreDefinition;
use myapp\model\SeanceDefinition;

class EpargneAdminController extends CrudAdminController
{
    protected function onCreate() {
        parent::onCreate();
        $this->modelDefinition = EpargneDefinition::getInstance();
    }
   
    protected function createFormHelper($update)
    {
        $helper = parent::createFormHelper($update);
        $helper->addExternalFieldDefinition('membreId', ExternalFieldEditionType::ALL_FIELDS);
        $helper->setTitle($this->l('New Saving'));
        return $helper;
    }
    protected function createViewHelper()
    {
        $helper = parent::createViewHelper();
        $helper->addExternalFieldDefinition('membreId', ExternalFieldViewType::ALL_FIELDS);
      
        return $helper;
    }

    protected function createListHelper()
    {
        $listHelper = parent::createListHelper();
        $definition = array(
            'otherFields' => array(
                'nom' => array('label' => $this->l('nom'))
            ),
            'hidden' => true
        );
        $listHelper->addExternalFieldDefinition('membreId', $definition);
        $listHelper->setSpecificSearchEnabled(true);
        $listHelper->setInnerSearchEnabled(false);
        $listHelper->setQuickSearchEnabled(true);
        $listHelper->setSpecificSortEnabled(true);
        $listHelper->setAjaxEnabled(true);
        return $listHelper;
    }
    
   
}
