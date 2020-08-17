<?php
namespace mymod;

use muuska\project\AbstractSubProject;

class MyModAdmin extends AbstractSubProject
{
    public function createController(\muuska\controller\ControllerInput $input) {
        $result = null;
        if ($input->checkName('home')) {
            $result = new \mymod\controller\admin\MyModHomeAdminController($input);
        }
        return $result;
    }
}
