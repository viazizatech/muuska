<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\TypeDefinition;

class TypeAdminController extends CrudController
{
    protected function onCreate() {
        $this->modelDefinition = TypeDefinition::getInstance();
    }
}
