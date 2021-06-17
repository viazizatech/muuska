<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\ProfilDefinition;

class PosteAdminController extends CrudController
{
    protected function onCreate() {
        parent::onCreate();
        $this->modelDefinition = ProfilDefinition::getInstance();
    }
}
