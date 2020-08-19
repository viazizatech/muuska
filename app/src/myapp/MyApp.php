<?php
namespace myapp;

use muuska\project\AbstractApplication;
use muuska\util\App;
use muuska\project\constants\SubAppName;


class MyApp extends AbstractApplication
{
    protected $version = '1.0';
    
    protected function registerMainDAOSources(){
        parent::registerMainDAOSources();
        $this->registerDaoSource(App::daos()->createPDOSourceFromConfiguration());
    }
    
    protected function createSubProject($subAppName){
        if($subAppName === SubAppName::FRONT_OFFICE){
            return new FrontSubApplication($subAppName, $this);
        }elseif($subAppName === SubAppName::BACK_OFFICE){
            return new AdminSubApplication($subAppName, $this);
        }
    }
    
    protected function createAppSetup()
    {
        return new \myapp\setup\AppSetup($this);
    }
    
    protected function createUpgrade(){
        $daoInput = App::daos()->createProjectDAOUpgradeInput($this);       
        return App::projects()->createDefaultProjectUpgrade($this, $this->daoFactory, $daoInput);
    }
}
