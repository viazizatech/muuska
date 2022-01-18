<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\SpecialityDefinition;

class SpecialityAdminController extends CrudController
{
    protected function onCreate() {
        parent::onCreate();
        $this->modelDefinition = SpecialityDefinition::getInstance();
    }
}
