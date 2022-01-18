<?php
namespace myapp;

use muuska\project\AbstractApplication;
use muuska\util\App;
use muuska\project\constants\SubAppName;
use myapp\model\EmpruntDefinition;
use myapp\model\TimbreDefinition;
use myapp\model\EpargneDefinition;
use myapp\model\MembreDefinition;
use myapp\model\SouscrireDefinition;
use myapp\model\SanctionDefinition ;
use myapp\model\RemboursementDefinition;
use myapp\model\ ProfilDefinition;
use myapp\model\InteretDefinition ;
use myapp\model\ FondDefinition;
use myapp\model\ ExerciceDefinition;
use myapp\model\EtablirDefinition;
use myapp\model\EpargnerDefinition ;
use myapp\model\AssociationDefinition;
use myapp\model\TontineDefinition ;
use myapp\model\AnnonceDefinition;
use myapp\model\SeanceDefinition;
use myapp\model\AssociationMembreDefinition;
use myapp\model\UtilisateurDefinition;


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
    
    protected function createUpgradeold(){
        $daoInput = App::daos()->createProjectDAOUpgradeInput($this);
       
        $daoInput->addAddedModelDefinition(AssociationDefinition::getInstance());
        $daoInput->addAddedModelDefinition(EmpruntDefinition::getInstance());
        $daoInput->addAddedModelDefinition(TimbreDefinition ::getInstance());
        $daoInput->addAddedModelDefinition(EpargneDefinition::getInstance());
        $daoInput->addAddedModelDefinition(MembreDefinition::getInstance());
        $daoInput->addAddedModelDefinition(SouscrireDefinition ::getInstance());
        $daoInput->addAddedModelDefinition(SanctionDefinition ::getInstance());
        $daoInput->addAddedModelDefinition(RemboursementDefinition::getInstance());
        $daoInput->addAddedModelDefinition(ProfilDefinition::getInstance());
        $daoInput->addAddedModelDefinition(InteretDefinition::getInstance());
        $daoInput->addAddedModelDefinition(FondDefinition::getInstance());
        $daoInput->addAddedModelDefinition(ExerciceDefinition::getInstance());
        $daoInput->addAddedModelDefinition(EtablirDefinition::getInstance());
        $daoInput->addAddedModelDefinition(EpargnerDefinition ::getInstance());
        $daoInput->addAddedModelDefinition(TontineDefinition::getInstance());
        $daoInput->addAddedModelDefinition(AnnonceDefinition::getInstance());
        $daoInput->addAddedModelDefinition(SeanceDefinition::getInstance());
        $daoInput->addAddedModelDefinition(AssociationMembreDefinition::getInstance());
        return App::projects()->createDefaultProjectUpgrade($this, $this->daoFactory, $daoInput);
    }
    
   protected function createUpgrade(){
        $daoInput = App::daos()->createProjectDAOUpgradeInput($this);
        
        /*Supprimer les models dont la définition a changé*/
      
        $daoInput->addRemovedModelDefinition(UtilisateurDefinition::getInstance());
        $daoInput->addAddedModelDefinition(UtilisateurDefinition::getInstance());
      
        
        
        /*Ajouter les nouveaux models et des models dont définition a changé*/
        
        return App::projects()->createDefaultProjectUpgrade($this, $this->daoFactory, $daoInput);
    }
}
