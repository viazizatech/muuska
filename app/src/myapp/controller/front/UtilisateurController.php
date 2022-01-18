<?php
namespace myapp\controller\front;

use muuska\controller\CrudController;
use myapp\model\UtilisateurDefinition;
use muuska\html\constants\AlertType;

class UtilisateurController extends CrudController
{	
	protected function onCreate(){
        parent::onCreate();
        $this->modelDefinition = UtilisateurDefinition::getInstance();
    }
    protected function createListHelper(){
        $listHelper = parent::createListHelper();
        $listHelper->setSpecificSearchEnabled(true);
        $listHelper->setInnerSearchEnabled(false);
        $listHelper->setQuickSearchEnabled(true);
        $listHelper->setSpecificSortEnabled(true);
        return $listHelper;
    }
}