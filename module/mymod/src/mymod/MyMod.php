<?php
namespace mymod;

use muuska\project\AbstractProject;
use muuska\project\constants\SubAppName;

class MyMod extends AbstractProject
{
    protected $name = 'mymod';
    
    protected function createSubProject($subAppName){
        if($subAppName === SubAppName::FRONT_OFFICE){
            return new MyModFront($subAppName, $this);
        }elseif($subAppName === SubAppName::BACK_OFFICE){
            return new MyModAdmin($subAppName, $this);
        }
    }
}
