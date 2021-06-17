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
use myapp\model\Epargne;
use myapp\model\MembreDefinition;
use myapp\model\ExerciceDefinition;
use myapp\model\SeanceDefinition;
use myapp\model\EpargneDefinition;
use myapp\model\EmpruntDefinition;
use muuska\dao\AbstractDAO;
use myapp\model\Emprunt;

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
         
         
         $libSpeciality2 = AssociationMembreDefinition::getInstance()->createModel();
         $libSpeciality2->setPropertyValue('membreId', $speciality2->getPropertyValue('id'));
         
         /*Modification de l'adresse de la bibliotheque*/
         
         /*Ajout des specialités a la bibliotheque*/
         $asso->addMultipleAssociated('specialities', $libSpeciality1);
         $asso->addMultipleAssociated('specialities', $libSpeciality2);
             /*Création du SaveConfig*/
        $saveConfig = $this->input->createSaveConfig();
        
        /*On demande qu'il associe les specialité à la bibliotèque après son enregistrement*/
        
    }
    protected function processSelectWithTotal() {
        $libraryDao = $this->input->getDAO(MembreDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $selectionConfig->setDataCountingEnabled(true);
        $selectionConfig->setLimit(1);
        $data = $libraryDao->getData($selectionConfig);
        var_dump('total without limit : ', $data->getTotalWithoutLimit());
        var_dump($data);
    }
    protected function processEpargneTotal() {
        $libraryDao = $this->input->getDAO(EpargneDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $selectionConfig->setDataCountingEnabled(true);
        $selectionConfig->setLimit(1);
        $data = $libraryDao->getData($selectionConfig);
        var_dump('total without limit : ', $data->getTotalWithoutLimit());
        var_dump($data);
    }
    protected function processSelectEpargne() {
        $libraryDao = $this->input->getDAO(EpargneDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $selectionConfig = $this->input->createSelectionConfig();
        $selectionConfig->getSpecificFields(('montant_epargne'));
        $data = $libraryDao->getModelValue($selectionConfig,'montant_epargne');
        var_dump($data);
        foreach ($data as $object) {
            $object->getPropertyValue('montant_epargne');
            
        }
        var_dump( $object->getPropertyValue('montant_epargne'));

    }
    protected function processEpargne() {
        $epargneDao = $this->input->getDAO(EpargneDefinition::getInstance());
        $epargne = new Epargne();
        $selectionConfig = $this->input->createSelectionConfig();
        $selectionConfig->setSelectionAssociationParams('membreId');
        $data = $epargneDao->getData($selectionConfig);
        var_dump($data);
        $table = App::htmls()->createTable($data );
        foreach ($data as $object) {
            
            var_dump($object->getAssociated('membreId'));
            print_r($object->getTotalMontant());
            
       
        }
    }
    public function processTotalMontant($montant_epargne){
        
        $epargne = $this->input->getDAO(EpargneDefinition::getInstance());
        foreach ($epargne as $epargne){
            $epargne = $montant_epargne += $this->montant_epargne;
            print_r($epargne) ;
        }
        
      
   }
    protected function processRestriction() {
        $libraryDao = $this->input->getDAO(AssociationDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $selectionConfig->setRestrictionFieldParams('nom_assoc', 'rares', Operator::CONTAINS);
        $data = $libraryDao->getData($selectionConfig);
        foreach ($data as $model){
            print_r($model->getPropertyValue('nom_assoc'));
            echo '<br>';
        }
    }
    protected function processEpar(){
        $dao = $this->input->getDAO(EpargneDefinition::getInstance());
        $data = $dao->getData();
        $montant_epargne = 0;
        $emprunt = new Emprunt;

        $interet = 0;
        foreach ($data as $epargne){
           
         $total_epargne = $montant_epargne += (float)$epargne->getPropertyValue('montant_epargne') ;
        }
        if($emprunt->getMontant_emprunt(100000)<$total_epargne){
            echo('ok');
        }else{echo('no');}
        
        print_r($total_epargne);
        return($total_epargne);    
    }
    protected function processCheckLoan($total_epargne){
        $dao = $this->input->getDAO(EmpruntDefinition::getInstance());
        $data = $dao->getData();
        if($data -> $this->montant_emprunt > $total_epargne  ){
            return true;
        }
        return false;    
    }
    protected function processwithdraw($total_epargne){
        $dao = $this->input->getDAO(EmpruntDefinition::getInstance());
        $data = $dao->getData();
        if($data -> $this->montant_emprunt > $total_epargne  ){
            return true;
        }
        return false;    
    }
    
    protected function processRestrictionAssociation() {
        $libraryDao = $this->input->getDAO(EpargneDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $nom = array('Paris', 'Washington');
        $restriction = App::daos()->createFieldRestriction('membreId', $nom, Operator::IN_LIST);
        $restriction->setForeign(true);
        $restriction->setExternalField('prenom');
        $selectionConfig->addRestrictionField($restriction, 'prenom');
        $selectionConfig->setSelectionAssociationParams('membreId');
        $data = $libraryDao->getData($selectionConfig);
        foreach ($data as $model){
            $address = $model->getAssociated('membreId');
            print_r($address->getPropertyValue('nom') . ' : ' . $model->getNom());
            echo '<br>';
        }
        var_dump( $data);
    }
    protected function processSortAssociation() {
        $libraryDao = $this->input->getDAO(EpargneDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $sortOption = App::daos()->createSortOption('membreId', SortDirection::ASC);
        $sortOption->setForeign(true);
        $sortOption->setExternalField('id');
        $selectionConfig->addSortOption($sortOption, 'id');
        $selectionConfig->setSelectionAssociationParams('membreId');
        $data = $libraryDao->getData($selectionConfig);
        $membre= array();
        $membre['nom']='';
        $membre['prenom']='';
        $membre['totalmembre']=0;
        $membre['interet']=0;
        $membre['global']=0;
        foreach ($data as  $model){
            $address = $model->getAssociated('membreId');
            //print_r($address->getPropertyValue('nom') . ' : ' . $model->getName());
            $membre['nom']=$address->getPropertyValue('nom');
            $membre['prenom']=$address->getPropertyValue('prenom');
            $membre['totalmembre']  = (float)$model->getPropertyValue('montant_epargne'); 
            $membre['interet'] = (float)$model->getPropertyValue('taux_epargne');
            $membre['global'] = (float)$model->getPropertyValue('taux_epargne');
              /* foreach ($data as $epargne){  
                $membre['totalmembre']  = (float)$epargne->getPropertyValue('montant_epargne'); 
               
                    
                print_r($membre );  
                }*/
            
                 
                print_r($membre );  
        }
        return $membre ;
        
    }
    
    
}
