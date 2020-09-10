<?php
namespace myapp\controller\front;

use muuska\controller\AbstractController;
use myapp\model\Library;
use myapp\constants\Accessibility;
use myapp\model\AddressDefinition;
use myapp\model\SpecialityDefinition;

class TestModelController extends AbstractController
{
    protected function processDefault()
    {
        /*Création de la bibliotheque*/
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
        $speciality1 = SpecialityDefinition::getInstance()->createModel();
        $speciality1->setPropertyValue('name', 'Art');
        
        $speciality2 = SpecialityDefinition::getInstance()->createModel();
        $speciality2->setPropertyValue('name', 'Musique');
        
        /*Modification de l'adresse de la bibliotheque*/
        $library->setAssociated('addressId', $address);
        
        /*Ajout des specialités a la bibliotheque*/
        $library->addMultipleAssociated('specialities', $speciality1);
        $library->addMultipleAssociated('specialities', $speciality2);
        
        /*Affichage*/
        var_dump('bibliotheque : ', $library);
        var_dump('adresse : ', $library->getAssociated('addressId'));;
        var_dump('specialités : ', $library->getMultipleAssociatedModels('specialities'));
    }
    
    protected function processTestLang()
    {
        $library = new Library();
        $library->setPropertyValueByLang('name', 'Paris library', 'en');
        $library->setPropertyValueByLang('name', 'Bibliothèque de paris', 'fr');
        
        var_dump($library->getPropertyValueByLang('name', 'en'));
        var_dump($library->getPropertyValueByLang('name', 'fr'));
    }
    
    protected function processTestAllLang()
    {
        $library = new Library();
        $values = array(
            'en' => 'Paris library',
            'fr' => 'Bibliothèque de paris'
            
        );
        $library->setAllLangsPropertyValues('name', $values);
        var_dump($library->getAllLangsPropertyValues('name'));
    }
}
