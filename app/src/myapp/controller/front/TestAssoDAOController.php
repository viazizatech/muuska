<?php
namespace myapp\controller\front;
use muuska\constants\operator\LogicalOperator;
use muuska\constants\operator\Operator;
use muuska\controller\AbstractController;
use muuska\dao\constants\DAOFunctionCode;
use muuska\dao\constants\SortDirection;
use muuska\util\App;
use myapp\model\Association ;
use myapp\model\AssociationDefinition;
use myapp\model\ProfilDefinition;
use myapp\model\MembreDefinition;
use myapp\model\AssociationMembreDefinition;

class TestAssoDAOController extends AbstractController
{
    protected function processDefault()
    {
        $AssociationDao = $this->input->getDAO(AssociationDefinition::getInstance());
        $asso = new Association();
        $asso->setNom_Assoc('viaziza help');
        $asso->setSiege('Yaounde');
        $asso->setDevise('peace and love');
        /*creation d'un membre*/
     $membreDao = $this->input->getDAO(membreDefinition::getInstance());
    $membre1 = MembreDefinition::getInstance()->createModel();
    $membre1->setPropertyValue('nom', 'nga'); 
    $membre1->setPropertyValue('prenom', 'brice'); 
    $membre2 = MembreDefinition::getInstance()->createModel();
    $membre2->setPropertyValue('nom', 'boss'); 
    $membre2->setPropertyValue('prenom', 'pierre'); 
      /*Ajout de la specialité*/
     
      $assomembre1 = AssociationMembreDefinition::getInstance()->createModel();
      $assomembre1->setPropertyValue('membreId', $membre1->getPropertyValue('id'));
      
      $assomembre2 = AssociationMembreDefinition::getInstance()->createModel();
      $assomembre2->setPropertyValue('membreId', $membre2->getPropertyValue('id'));
      /*ajout des membres*/
      $asso->addMultipleAssociated('membre',$assomembre1 );
      $asso->addMultipleAssociated('membre', $assomembre1);
      var_dump($asso);
   /*Création du SaveConfig*/
       $saveConfig = $this->input->createSaveConfig();
        /*On demande qu'il associe les specialité à la bibliotèque après son enregistrement*/
        $saveConfig->createMultipleSaveAssociation('membre');
       
    }
    
    
}
