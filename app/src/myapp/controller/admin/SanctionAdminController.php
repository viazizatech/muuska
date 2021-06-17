<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\SanctionDefinition;

class SanctionAdminController extends CrudController
{
    protected function onCreate() {
        parent::onCreate();
        $this->modelDefinition = SanctionDefinition::getInstance();
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
