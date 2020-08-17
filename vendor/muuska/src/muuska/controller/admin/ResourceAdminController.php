<?php
namespace muuska\controller\admin;

use muuska\controller\CrudController;
use muuska\security\model\ResourceDefinition;

class ResourceAdminController extends CrudController
{	
	protected function onCreate()
    {
        parent::onCreate();
        $this->modelDefinition = ResourceDefinition::getInstance();
    }
    
    /*protected function createListHelper() {
        $listHelper = parent::createListHelper();
        $listHelper->setListType(ListType::TREE);
        return $listHelper;
    }*/
}