<?php
namespace myapp;

use muuska\project\AbstractSubApplication;
use muuska\util\App;
use myapp\model\PublisherDefinition;

class AdminSubApplication extends AbstractSubApplication
{
    public function createController(\muuska\controller\ControllerInput $input) {
        $result = null;
        if ($input->checkName('library')) {
            $result = new \myapp\controller\admin\LibraryAdminController($input);
        }elseif ($input->checkName('speciality')) {
            $result = new \myapp\controller\admin\SpecialityAdminController($input);
        }elseif ($input->checkName('type')) {
            $result = new \myapp\controller\admin\TypeAdminController($input);
        }elseif ($input->checkName('book')) {
            $result = new \myapp\controller\admin\BookAdminController($input);
        }elseif ($input->checkName('category')) {
            $result = new \myapp\controller\admin\CategoryAdminController($input);
        }elseif ($input->checkName('publisher')) {
            $result = App::controllers()->createVirtualCrudController($input, PublisherDefinition::getInstance());
        }
        return $result;
    }
}