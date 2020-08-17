<?php
namespace myapp;

use muuska\project\AbstractApplication;
use muuska\util\App;
use muuska\project\constants\SubAppName;
use myapp\model\SpecialityDefinition;
use myapp\model\TypeDefinition;
use myapp\model\AddressDefinition;
use myapp\model\CategoryDefinition;
use myapp\model\PublisherDefinition;
use myapp\model\AuthorDefinition;
use myapp\model\LibraryDefinition;
use myapp\model\LibraryTypeDefinition;
use myapp\model\LibrarySpecialityDefinition;
use myapp\model\BookDefinition;

class MyApp extends AbstractApplication
{
    protected $version = '1.2';
    
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
    
    protected function createUpgradeOld(){
        $daoInput = App::daos()->createProjectDAOUpgradeInput($this);
        $daoInput->addAddedModelDefinition(SpecialityDefinition::getInstance());
        $daoInput->addAddedModelDefinition(TypeDefinition::getInstance());
        $daoInput->addAddedModelDefinition(AddressDefinition::getInstance());
        $daoInput->addAddedModelDefinition(CategoryDefinition::getInstance());
        $daoInput->addAddedModelDefinition(PublisherDefinition::getInstance());
        $daoInput->addAddedModelDefinition(AuthorDefinition::getInstance());
        $daoInput->addAddedModelDefinition(LibraryDefinition::getInstance());
        $daoInput->addAddedModelDefinition(LibraryTypeDefinition::getInstance());
        $daoInput->addAddedModelDefinition(LibrarySpecialityDefinition::getInstance());
        $daoInput->addAddedModelDefinition(BookDefinition::getInstance());
        return App::projects()->createDefaultProjectUpgrade($this, $this->daoFactory, $daoInput);
    }
    
    protected function createUpgrade(){
        $daoInput = App::daos()->createProjectDAOUpgradeInput($this);
        
        /*Supprimer les models dont la définition a changé*/
        $daoInput->addRemovedModelDefinition(SpecialityDefinition::getInstance());
        $daoInput->addRemovedModelDefinition(TypeDefinition::getInstance());
        $daoInput->addRemovedModelDefinition(CategoryDefinition::getInstance());
        $daoInput->addRemovedModelDefinition(PublisherDefinition::getInstance());
        $daoInput->addRemovedModelDefinition(LibraryDefinition::getInstance());
        $daoInput->addRemovedModelDefinition(BookDefinition::getInstance());
        
        /*Ajouter les nouveaux models et des models dont définition a changé*/
        $daoInput->addAddedModelDefinition(SpecialityDefinition::getInstance());
        $daoInput->addAddedModelDefinition(TypeDefinition::getInstance());
        $daoInput->addAddedModelDefinition(CategoryDefinition::getInstance());
        $daoInput->addAddedModelDefinition(PublisherDefinition::getInstance());
        $daoInput->addAddedModelDefinition(LibraryDefinition::getInstance());
        $daoInput->addAddedModelDefinition(BookDefinition::getInstance());
        
        return App::projects()->createDefaultProjectUpgrade($this, $this->daoFactory, $daoInput);
    }
}
