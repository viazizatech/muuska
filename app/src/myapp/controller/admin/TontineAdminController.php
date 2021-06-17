<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\TontineDefinition;

class TontineAdminController extends CrudController
{
    protected function onCreate() {
        $this->modelDefinition = TontineDefinition::getInstance();
    }
    protected function createListHelper()
    {
        $listHelper = parent::createListHelper();
        $definition = array(
            'otherFields' => array(
                'nom_assoc' => array('label' => $this->l('nom'))
            ),
            'hidden' => true
        );
        $listHelper->addExternalFieldDefinition('associationId', $definition);
        $listHelper->setSpecificSearchEnabled(true);
        $listHelper->setInnerSearchEnabled(false);
        $listHelper->setQuickSearchEnabled(true);
        $listHelper->setSpecificSortEnabled(true);
        $listHelper->setAjaxEnabled(true);
        return $listHelper;
    }
}
