<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\TimbreDefinition;

class TimbreAdminController extends CrudController
{
    protected function onCreate() {
        parent::onCreate();
        $this->modelDefinition = TimbreDefinition::getInstance();
    }
}
