<?php
namespace myapp\controller\front;
use muuska\constants\operator\LogicalOperator;
use muuska\constants\operator\Operator;
use muuska\controller\AbstractController;
use muuska\dao\constants\DAOFunctionCode;
use muuska\dao\constants\SortDirection;
use muuska\util\App;
use myapp\model\Membre;
use myapp\constants\Accessibility;
use myapp\model\Association ;
use myapp\model\AssociationDefinition;
use myapp\model\AssociationMembreDefinition;
use myapp\model\MembreDefinition;
use myapp\model\ExerciceDefinition;
use myapp\model\SeanceDefinition;
class TestDAOController extends AbstractController
{
    protected function processDefault()
    {
        $AssociationDao = $this->input->getDAO(AssociationDefinition::getInstance());     
        $asso = new Association();
        $asso->setNom_assoc('vaiziza');
        $asso->setSiege('Yaounde');
        $asso->setDevise('peace and love');
        $asso->setAccessibility(Accessibility::PUBLIC);
        /*Création d'un membre*/
        
        /*ajou d'un membre*/
       
        
        /*Création du SaveConfig*/
        $saveConfig = $this->input->createSaveConfig();
        /*On demande d'enregistrer le profil avant d'enregistrer de l'association*/
        $saveConfig->createAssociatedFieldSaveConfig('membreId');
        
       
        
       
    }
    
    protected function processTestAllLang()
    {
        $library = new Association();
        $values = array(
            'en' => 'francais',
            'fr' => 'Anglais'
            
        );
        $library->setAllLangsPropertyValues('id', $values);
        var_dump($library->getAllLangsPropertyValues('id'));
    }

    protected function processUpdate() {
        $associationDao = $this->input->getDAO(AssociationDefinition::getInstance());
        var_dump($associationDao);
        $asso = $associationDao->getById(4);
        $asso->setNom_assoc('vaiziza v2');
        $asso->setAccessibility(Accessibility::PRIVATE);
        $associationDao->update($asso);
        
    }
    protected function processSelect() {
        $associationDao = $this->input->getDAO(AssociationDefinition::getInstance());
        $data = $associationDao->getData();
        var_dump($data);
    }
    
    protected function processDelete() {
        $associationDao = $this->input->getDAO(AssociationDefinition::getInstance());
        $asso= $associationDao->getById(4);
        $associationDao->delete($asso);
    }
    protected function processChangeValue() {
        $AssociationDao = $this->input->getDAO(AssociationDefinition::getInstance());
        $saveConfig = $this->input->createSaveConfig();
        
        /*Ajout des options pour la restriction*/
        $saveConfig->addRestrictionFieldFromParams('phone_respon', '1789550');
        
        $AssociationDao->changeValueOnMultipleRows('nom_assoc', 'viaziza new look', $saveConfig);
    }
    
    protected function processSelectAssociation() {
        $associationDao = $this->input->getDAO(AssociationMembreDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $selectionConfig->setSelectionAssociationParams('membreId');
        $data = $associationDao->getData($selectionConfig);
        foreach ($data as $object) {
            var_dump($object->getAssociated('membreId'));
        }
        
    } 
    protected function processAddMembre() {
       echo('bonjour');
       $AssociationDao = $this->input->getDAO(AssociationDefinition::getInstance());     
        $asso = new Association();
        $asso->setNom_assoc('vaiziza');
         /*Création de la specialité*/
         $specialityDao = $this->input->getDAO(MembreDefinition::getInstance());
         $speciality1 = MembreDefinition::getInstance()->createModel();
         $speciality1->setPropertyValue('nom', 'Art');
         var_dump($speciality1);
         /*Ajout de la specialité*/
         $specialityDao->add($speciality1);
         
         $libSpeciality1 = AssociationMembreDefinition::getInstance()->createModel();
         $libSpeciality1->setPropertyValue('membreId', $speciality1->getPropertyValue('id'));
         
         $speciality2 = MembreDefinition::getInstance()->createModel();
         $speciality2->setPropertyValue('nom', 'Musique');
         
         
         /*Ajout de la specialité*/
         $specialityDao->add($speciality2);
         
         $libSpeciality2 = AssociationMembreDefinition::getInstance()->createModel();
         $libSpeciality2->setPropertyValue('membreId', $speciality2->getPropertyValue('id'));
         
         /*Modification de l'adresse de la bibliotheque*/
         
         /*Ajout des specialités a la bibliotheque*/
         $asso->addMultipleAssociated('specialities', $libSpeciality1);
         $asso->addMultipleAssociated('specialities', $libSpeciality2);
             /*Création du SaveConfig*/
        $saveConfig = $this->input->createSaveConfig();
        
        /*On demande qu'il associe les specialité à la bibliotèque après son enregistrement*/
        $saveConfig->createMultipleSaveAssociation('membre');
        $AssociationDao->add($asso, $saveConfig);
        $AssociationDao->add($asso, $saveConfig);
    }
}
