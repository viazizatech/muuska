<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\ExerciceDefinition;

class ExerciceAdminController extends CrudController
{
    protected function onCreate() {
        parent::onCreate();
        $this->modelDefinition = ExerciceDefinition::getInstance();
    }
    
}
