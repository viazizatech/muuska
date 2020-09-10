<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\SeanceDefinition;

class CategoryAdminController extends CrudController
{
    protected function onCreate() {
        $this->modelDefinition = SeanceDefinition::getInstance();
    }
}
