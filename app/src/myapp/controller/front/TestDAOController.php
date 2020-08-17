<?php
namespace myapp\controller\front;
use muuska\constants\operator\LogicalOperator;
use muuska\constants\operator\Operator;
use muuska\controller\AbstractController;
use muuska\dao\constants\DAOFunctionCode;
use muuska\dao\constants\SortDirection;
use muuska\util\App;
use myapp\constants\Accessibility;
use myapp\model\AddressDefinition;
use myapp\model\Library;
use myapp\model\LibraryDefinition;
use myapp\model\LibrarySpecialityDefinition;
use myapp\model\SpecialityDefinition;
class TestDAOController extends AbstractController
{
    protected function processDefault()
    {
        $libraryDao = $this->input->getDAO(LibraryDefinition::getInstance());
        $library = new Library();
        $library->setName('My library');
        $library->setOpeningTime('Monday');
        $library->setAccessibility(Accessibility::PUBLIC);
        $library->setDescription('My library desc');
        /*Création de l'adresse*/
        $address = AddressDefinition::getInstance()->createModel();
        $address->setPropertyValue('address', '4500 NY');
        $address->setPropertyValue('city', 'New york');
        $address->setPropertyValue('state', 'New york');
        $address->setPropertyValue('country', 'US');
        
        /*Création de la specialité*/
        $specialityDao = $this->input->getDAO(SpecialityDefinition::getInstance());
        $speciality1 = SpecialityDefinition::getInstance()->createModel();
        $speciality1->setPropertyValue('name', 'Art');
        
        /*Ajout de la specialité*/
        $specialityDao->add($speciality1);
        
        $libSpeciality1 = LibrarySpecialityDefinition::getInstance()->createModel();
        $libSpeciality1->setPropertyValue('specialityId', $speciality1->getPropertyValue('id'));
        
        $speciality2 = SpecialityDefinition::getInstance()->createModel();
        $speciality2->setPropertyValue('name', 'Musique');
        
        /*Ajout de la specialité*/
        $specialityDao->add($speciality2);
        
        $libSpeciality2 = LibrarySpecialityDefinition::getInstance()->createModel();
        $libSpeciality2->setPropertyValue('specialityId', $speciality2->getPropertyValue('id'));
        
        /*Modification de l'adresse de la bibliotheque*/
        $library->setAssociated('addressId', $address);
        
        /*Ajout des specialités a la bibliotheque*/
        $library->addMultipleAssociated('specialities', $libSpeciality1);
        $library->addMultipleAssociated('specialities', $libSpeciality2);
        
        /*Création du SaveConfig*/
        $saveConfig = $this->input->createSaveConfig();
        
        /*On demande d'enregistrer l'addresse avant d'enregistrer la bibliotèque*/
        $saveConfig->createAssociatedFieldSaveConfig('addressId');
        
        /*On demande qu'il associe les specialité à la bibliotèque après son enregistrement*/
        $saveConfig->createMultipleSaveAssociation('specialities');
        $libraryDao->add($library, $saveConfig);
    }
    
    protected function processUpdate() {
        $libraryDao = $this->input->getDAO(LibraryDefinition::getInstance());
        $library = $libraryDao->getById(8);
        $library->setName('My library updt');
        $library->setAccessibility(Accessibility::PRIVATE);
        $libraryDao->update($library);
    }
    
    protected function processMultipleUpdate() {
        $libraryDao = $this->input->getDAO(LibraryDefinition::getInstance());
        $library = new Library();
        $library->setName('My library updt multiple');
        $library->setAccessibility(Accessibility::PRIVATE);
        $saveConfig = $this->input->createSaveConfig();
        
        /*Ajout des options pour la restriction*/
        $saveConfig->addRestrictionFieldFromParams('openingTime', 'Monday');
        
        $libraryDao->updateMultipleRows($library, $saveConfig);
    }
    
    protected function processChangeValue() {
        $libraryDao = $this->input->getDAO(LibraryDefinition::getInstance());
        $saveConfig = $this->input->createSaveConfig();
        
        /*Ajout des options pour la restriction*/
        $saveConfig->addRestrictionFieldFromParams('openingTime', 'Monday');
        
        $libraryDao->changeValueOnMultipleRows('name', 'My library value changed', $saveConfig);
    }
    
    protected function processSelect() {
        $libraryDao = $this->input->getDAO(LibraryDefinition::getInstance());
        $data = $libraryDao->getData();
        var_dump($data);
    }
    
    protected function processSelectWithTotal() {
        $libraryDao = $this->input->getDAO(LibraryDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $selectionConfig->setDataCountingEnabled(true);
        $selectionConfig->setLimit(1);
        $data = $libraryDao->getData($selectionConfig);
        var_dump('total without limit : ', $data->getTotalWithoutLimit());
        var_dump($data);
    }
    
    protected function processSelectAssociation() {
        $libraryDao = $this->input->getDAO(LibraryDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $selectionConfig->setSelectionAssociationParams('addressId');
        $data = $libraryDao->getData($selectionConfig);
        foreach ($data as $object) {
            var_dump($object->getAssociated('addressId'));
        }
    }
    
    protected function processSort() {
        $libraryDao = $this->input->getDAO(LibraryDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $selectionConfig->setSortOptionParams('name', SortDirection::DESC);
        $data = $libraryDao->getData($selectionConfig);
        foreach ($data as $model){
            print_r($model->getName());
            echo '<br>';
        }
    }
    
    protected function processSortAssociation() {
        $libraryDao = $this->input->getDAO(LibraryDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $sortOption = App::daos()->createSortOption('addressId', SortDirection::ASC);
        $sortOption->setForeign(true);
        $sortOption->setExternalField('country');
        $selectionConfig->addSortOption($sortOption, 'country');
        $selectionConfig->setSelectionAssociationParams('addressId');
        $data = $libraryDao->getData($selectionConfig);
        foreach ($data as $model){
            $address = $model->getAssociated('addressId');
            print_r($address->getPropertyValue('country') . ' : ' . $model->getName());
            echo '<br>';
        }
    }
    
    protected function processRestriction() {
        $libraryDao = $this->input->getDAO(LibraryDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $selectionConfig->setRestrictionFieldParams('name', 'rares', Operator::CONTAINS);
        $data = $libraryDao->getData($selectionConfig);
        foreach ($data as $model){
            print_r($model->getName());
            echo '<br>';
        }
    }
    
    protected function processRestrictionAssociation() {
        $libraryDao = $this->input->getDAO(LibraryDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $cities = array('Paris', 'Washington');
        $restriction = App::daos()->createFieldRestriction('addressId', $cities, Operator::IN_LIST);
        $restriction->setForeign(true);
        $restriction->setExternalField('city');
        $selectionConfig->addRestrictionField($restriction, 'city');
        $selectionConfig->setSelectionAssociationParams('addressId');
        $data = $libraryDao->getData($selectionConfig);
        foreach ($data as $model){
            $address = $model->getAssociated('addressId');
            print_r($address->getPropertyValue('city') . ' : ' . $model->getName());
            echo '<br>';
        }
    }
    
    protected function processSubRestriction() {
        $libraryDao = $this->input->getDAO(LibraryDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $selectionConfig->setRestrictionFieldParams('openingTime', 'jeudi', Operator::CONTAINS);
        
        $restriction = App::daos()->createFieldRestriction('addressId', null);
        $restriction->setLogicalOperator(LogicalOperator::OR_);
        $restriction->addSubFieldFromParams('addressId', 'France', Operator::EQUALS, 'country', true, 'country');
        $restriction->addSubFieldFromParams('addressId', 'Boston', Operator::EQUALS, 'city', true, 'city');
        $selectionConfig->setSelectionAssociationParams('addressId');
        $selectionConfig->addRestrictionField($restriction, 'country_city');
        $data = $libraryDao->getData($selectionConfig);
        foreach ($data as $model){
            $address = $model->getAssociated('addressId');
            print_r($address->getPropertyValue('city') . ' : ' . $model->getName());
            echo '<br>';
        }
    }
    
    protected function processFunctionRestriction() {
        $libraryDao = $this->input->getDAO(LibraryDefinition::getInstance());
        $selectionConfig = $this->input->createSelectionConfig();
        $restriction = App::daos()->createFieldRestriction('addressId', '4500 NY New york US');
        $daoFunction = App::daos()->createDAOFunction(DAOFunctionCode::CONCAT);
        $daoFunction->addFieldParameter('addressId', true, 'address');
        $daoFunction->addSimpleParameter(' ');
        $daoFunction->addFieldParameter('addressId', true, 'city');
        $daoFunction->addSimpleParameter(' ');
        $daoFunction->addFieldParameter('addressId', true, 'country');
        $restriction->setDaoFunction($daoFunction);
        $selectionConfig->setSelectionAssociationParams('addressId');
        $selectionConfig->addRestrictionField($restriction, 'country_city');
        $data = $libraryDao->getData($selectionConfig);
        foreach ($data as $model){
            $address = $model->getAssociated('addressId');
            print_r($model->getName());
            var_dump($address);
            echo '<br>';
        }
    }
    
    protected function processDelete() {
        $libraryDao = $this->input->getDAO(LibraryDefinition::getInstance());
        $library = $libraryDao->getById(8);
        $libraryDao->delete($library);
    }
    
    protected function processMultipleDelete() {
        $libraryDao = $this->input->getDAO(LibraryDefinition::getInstance());
        $deleteConfig = $this->input->createDeleteConfig();
        
        /*Ajout des options pour la restriction*/
        $deleteConfig->addRestrictionFieldFromParams('openingTime', 'Monday');
        
        $libraryDao->deleteMultipleRows($deleteConfig);
    }
}
