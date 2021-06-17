<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\SouscrireDefinition;

class SouscrireAdminController extends CrudController
{
    protected function onCreate() {
        parent::onCreate();
        $this->modelDefinition = SouscrireDefinition::getInstance();
    }
}
