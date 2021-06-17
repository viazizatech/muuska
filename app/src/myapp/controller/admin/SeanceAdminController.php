<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\SeanceDefinition;

class SeanceAdminController extends CrudController
{
    protected function onCreate() {
        $this->modelDefinition = SeanceDefinition::getInstance();
    }
    protected function createListHelper()
    {
        
        $listHelper = parent::createListHelper();
        $definition = array(
            'otherFields' => array(
                'nom_exercice' => array('label' => $this->l('nom_exercice'))
            ),
            'hidden' => true
        );
        $listHelper->addExternalFieldDefinition('exerciceId', $definition);
      
        return $listHelper;
    }
    
}
