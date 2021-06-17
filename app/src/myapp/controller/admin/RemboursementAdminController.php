<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\RemboursementDefinition;

class RemboursementAdminController extends CrudController
{
    protected function onCreate() {
        parent::onCreate();
        $this->modelDefinition = RemboursementDefinition::getInstance();
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
        
        $definition = array(
            'otherFields' => array(
                'montant_emprunt' => array('label' => $this->l('montant_emprunt'))
            ),
            'hidden' => true
        );
        $listHelper->addExternalFieldDefinition('empruntId', $definition);
        
        return $listHelper;
    }
}
