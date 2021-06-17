<?php
namespace myapp\controller\admin;

use muuska\controller\CrudController;
use myapp\model\AssociationMembreDefinition;

class EtreMembreAdminController extends CrudController
{
    protected function onCreate() {
        parent::onCreate();
        $this->modelDefinition = AssociationMembreDefinition::getInstance();
    }
}
