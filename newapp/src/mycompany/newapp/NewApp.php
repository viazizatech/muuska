<?php
namespace mycompany\newapp;

use muuska\project\AbstractApplication;
use muuska\project\constants\SubAppName;

class NewApp extends AbstractApplication
{
    protected function createSubProject($subAppName){
        if($subAppName === SubAppName::FRONT_OFFICE){
            return new FrontSubApplication($subAppName, $this);
        }
    }
    protected function createAppSetup()
    {
        return new \mycompany\newapp\setup\NewAppSetup($this);
    }
}
