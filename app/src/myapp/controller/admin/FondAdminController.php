<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\FondDefinition;

class FondAdminController extends CrudController
{
    protected function onCreate() {
        parent::onCreate();
        $this->modelDefinition = FondDefinition::getInstance();
    }
}
