<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\InteretDefinition;

class InteretAdminController extends CrudController
{
    protected function onCreate() {
        parent::onCreate();
        $this->modelDefinition = InteretDefinition::getInstance();
    }
}
