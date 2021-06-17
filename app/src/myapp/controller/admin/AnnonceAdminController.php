<?php
namespace myapp\controller\admin;

use  myapp\controller\admin\partial\CrudAdminController;
use myapp\model\AnnonceDefinition;
use muuska\constants\ExternalFieldEditionType;
class AnnonceAdminController extends CrudAdminController
{
    protected function onCreate() {
        parent::onCreate();
        $this->modelDefinition = AnnonceDefinition::getInstance();
    }
    
    protected function createFormHelper($update)
    {
        $helper = parent::createFormHelper($update);
        $helper->addExternalFieldDefinition('membreId', ExternalFieldEditionType::ALL_FIELDS);
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
      
        return $listHelper;
    }
}
