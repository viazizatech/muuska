<?php
namespace myapp\controller\front;

use muuska\controller\AbstractController;
use myapp\model\Membre;
use myapp\constants\Accessibility;
use myapp\model\EmpruntDefinition;
use myapp\model\EpargneDefinition;
use myapp\model\MembreDefinition;
use myapp\model\AssociationDefinition;

class AssoTestModelController extends AbstractController
{
    protected function processDefault()
    {
        $MembreDao = $this->input->getDAO(MembreDefinition::getInstance());
        /*Création d'un membre*/
        $membre = new Membre ();
        $membre->setName('NGA');
        $membre->setPrenom('BRICE');
        $membre->setPhoto('My photo');
        
        
        /*Création d'un emprunt*/
        $emprunt = EmpruntDefinition::getInstance()->createModel();
        $emprunt->setPropertyValue('montant_emprunt', '4500 ');
        $emprunt->setPropertyValue('taux_emprunt', '3%');
        $emprunt->setPropertyValue('date_emprunt', '20/08/2020');
        
        
        /*Création d'une epargne*/
        $epargne1 = EpargneDefinition ::getInstance()->createModel();
        $epargne1->setPropertyValue('montant_epargne', '30000');
        
        $epargne2 =EmpruntDefinition::getInstance()->createModel();
        $epargne2->setPropertyValue('montant_epargne', '30000');
        
        /*Ajout des specialités a la bibliotheque*/
        $membre->addMultipleAssociated('epargne', $epargne1);
        $membre->addMultipleAssociated('epargne', $epargne2);
        $membre->setAssociated('empruntId', $emprunt);
        $membre->setAssociated('associationId', $emprunt);
        /*on demande d'enregistrer le membre avant l'emprunt*/
         $saveConfig = $this->input->createSaveConfig();
         $saveConfig->createAssociatedFieldSaveConfig('empruntId');
        /*Affichage*/
        var_dump('membre: ', $membre);
        var_dump('emprunt : ', $membre->getMultipleAssociatedModels('epargne'));
        var_dump('emprunt : ', $membre->getAssociated('emprunt'));
        

    }
    
    protected function processSelectAssociation() {
        $libraryDao = $this->input->getDAO(AssociationDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $selectionConfig->setSelectionAssociationParams('addressId');
        $data = $libraryDao->getData($selectionConfig);
        foreach ($data as $object) {
            var_dump($object->getAssociated('addressId'));
        }
    }
}
