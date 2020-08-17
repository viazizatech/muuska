<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\CategoryDefinition;

class CategoryAdminController extends CrudController
{
    protected function onCreate() {
        $this->modelDefinition = CategoryDefinition::getInstance();
    }
}
